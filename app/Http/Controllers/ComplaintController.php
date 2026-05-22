<?php
// app/Http/Controllers/ComplaintController.php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Notification;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * List complaints (Admin sees all; user sees own; staff sees assigned).
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Complaint::with(['user', 'wasteCategory', 'assignedStaff']);

        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isStaff()) {
            $query->where('assigned_to', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('waste_category_id', $request->category);
        }
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('complaint_number', 'like', "%{$request->search}%")
                  ->orWhere('area', 'like', "%{$request->search}%");
            });
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();
        $categories = WasteCategory::all();
        $areas      = Complaint::distinct()->pluck('area');

        return view('complaints.index', compact('complaints', 'categories', 'areas'));
    }

    /**
     * Show the submit complaint form.
     */
    public function create()
    {
        $categories = WasteCategory::all();
        return view('complaints.create', compact('categories'));
    }

    /**
     * Store a new complaint.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_category_id'  => 'required|exists:waste_categories,id',
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|min:10',
            'building'           => 'nullable|string|max:100',
            'area'               => 'required|string|max:100',
            'specific_location'  => 'nullable|string|max:255',
            'priority'           => 'required|in:normal,high,urgent',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
        }

        $complaint = Complaint::create(array_merge($validated, [
            'user_id'    => Auth::id(),
            'image_path' => $imagePath,
            'status'     => 'pending',
        ]));

        // Award points for reporting
        Auth::user()->addPoints(10);

        // Notify admin
        foreach (User::admins()->active()->get() as $admin) {
            Notification::send(
                $admin->id,
                'New complaint filed',
                "#{$complaint->complaint_number} — {$complaint->title} ({$complaint->area})",
                'complaint',
                route('complaints.show', $complaint)
            );
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint submitted successfully! Your ID is ' . $complaint->complaint_number);
    }

    /**
     * Show a single complaint with timeline.
     */
    public function show(Complaint $complaint)
    {
        // Authorization: user can only view own, staff only assigned
        $user = Auth::user();
        if ($user->isUser() && $complaint->user_id !== $user->id) {
            abort(403);
        }
        if ($user->isStaff() && $complaint->assigned_to !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $complaint->load(['user', 'wasteCategory', 'assignedStaff', 'feedback']);
        return view('complaints.show', compact('complaint'));
    }

    /**
     * Admin: assign complaint to staff and update status.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status'           => 'sometimes|in:pending,in_progress,resolved,closed',
            'assigned_to'      => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
            'priority'         => 'sometimes|in:normal,high,urgent',
        ]);

        $oldStatus = $complaint->status;

        if (isset($validated['status']) && $validated['status'] === 'resolved') {
            $validated['resolved_at'] = now();
        }

        $complaint->update($validated);

        // Notify the complaint submitter of status change
        if ($oldStatus !== $complaint->status) {
            Notification::send(
                $complaint->user_id,
                'Complaint status updated',
                "#{$complaint->complaint_number} is now " . ucfirst(str_replace('_', ' ', $complaint->status)),
                'complaint',
                route('complaints.show', $complaint)
            );

            // Points for valid resolution
            if ($complaint->status === 'resolved') {
                $complaint->user->addPoints(20);
            }
        }

        // Notify assigned staff
        if (isset($validated['assigned_to']) && $validated['assigned_to']) {
            Notification::send(
                $validated['assigned_to'],
                'Complaint assigned to you',
                "#{$complaint->complaint_number} — {$complaint->title}",
                'task',
                route('complaints.show', $complaint)
            );
        }

        return back()->with('success', 'Complaint updated successfully.');
    }

    /**
     * Public: track by complaint number.
     */
    public function track(Request $request)
    {
        $complaint = null;

        if ($request->filled('number')) {
            $complaint = Complaint::where('complaint_number', strtoupper($request->number))
                ->with(['wasteCategory', 'assignedStaff'])
                ->first();

            if (! $complaint) {
                return back()->withErrors(['number' => 'Complaint not found. Please check the ID.']);
            }
        }

        return view('complaints.track', compact('complaint'));
    }

    /**
     * Delete a complaint (admin only).
     */
    public function destroy(Complaint $complaint)
    {
        if ($complaint->image_path) {
            Storage::disk('public')->delete($complaint->image_path);
        }
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'Complaint deleted.');
    }
}
