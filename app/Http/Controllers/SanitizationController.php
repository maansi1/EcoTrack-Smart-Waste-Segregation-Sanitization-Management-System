<?php
// app/Http/Controllers/SanitizationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\SanitizationTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanitizationController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = SanitizationTask::with(['assignedStaff', 'createdBy']);

        if ($user->isStaff()) {
            $query->where('assigned_to', $user->id);
        }

        $tasks        = $query->orderBy('scheduled_date')->orderBy('scheduled_time')->paginate(20);
        $staffList    = User::staff()->active()->get();
        $todayTasks   = SanitizationTask::today()->when($user->isStaff(), fn($q) => $q->where('assigned_to', $user->id))->get();
        $completionRate = SanitizationTask::count() > 0
            ? round((SanitizationTask::completed()->count() / SanitizationTask::count()) * 100)
            : 0;

        return view('sanitization.index', compact('tasks', 'staffList', 'todayTasks', 'completionRate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assigned_to'    => 'required|exists:users,id',
            'area'           => 'required|string|max:100',
            'task_type'      => 'required|string|max:100',
            'description'    => 'nullable|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
        ]);

        $task = SanitizationTask::create(array_merge($validated, [
            'created_by' => Auth::id(),
            'status'     => 'pending',
        ]));

        // Notify assigned staff
        Notification::send(
            $task->assigned_to,
            'New sanitization task assigned',
            "{$task->task_type} at {$task->area} on {$task->scheduled_date->format('d M Y')} at {$task->scheduled_time}",
            'task'
        );

        return back()->with('success', 'Task scheduled and staff notified.');
    }

    public function update(Request $request, SanitizationTask $sanitizationTask)
    {
        $validated = $request->validate([
            'status'             => 'required|in:pending,in_progress,completed,cancelled',
            'completion_percent' => 'required|integer|min:0|max:100',
            'completion_notes'   => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_at']      = now();
            $validated['completion_percent'] = 100;
        }

        $sanitizationTask->update($validated);

        return back()->with('success', 'Task status updated.');
    }
}
