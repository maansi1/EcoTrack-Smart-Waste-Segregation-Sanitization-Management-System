<?php
// app/Http/Controllers/FeedbackController.php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $resolvedComplaints = Complaint::where('user_id', Auth::id())
            ->where('status', 'resolved')
            ->whereDoesntHave('feedback')
            ->get();

        $myFeedback = Feedback::where('user_id', Auth::id())->latest()->paginate(10);

        return view('feedback.index', compact('resolvedComplaints', 'myFeedback'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint_id' => 'nullable|exists:complaints,id',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:500',
            'area'         => 'nullable|string|max:100',
            'type'         => 'required|in:complaint,area_cleanliness,general',
        ]);

        // Prevent duplicate feedback on same complaint
        if (! empty($validated['complaint_id'])) {
            $exists = Feedback::where('user_id', Auth::id())
                ->where('complaint_id', $validated['complaint_id'])->exists();
            if ($exists) {
                return back()->withErrors(['complaint_id' => 'You have already rated this complaint.']);
            }
        }

        Feedback::create(array_merge($validated, ['user_id' => Auth::id()]));

        Auth::user()->addPoints(5); // points for giving feedback

        return back()->with('success', 'Thank you for your feedback! +5 points added.');
    }
}
