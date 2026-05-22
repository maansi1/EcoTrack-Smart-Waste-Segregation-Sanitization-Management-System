<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\SanitizationTask;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Admin dashboard ───────────────────────────────────────────────────
        if ($user->isAdmin()) {
            $stats = [
                'total_complaints'   => Complaint::count(),
                'pending'            => Complaint::pending()->count(),
                'in_progress'        => Complaint::inProgress()->count(),
                'resolved'           => Complaint::resolved()->count(),
                'full_bins'          => Bin::needsCollection()->count(),
                'total_users'        => User::users()->count(),
                'total_staff'        => User::staff()->count(),
                'tasks_today'        => SanitizationTask::today()->count(),
                'unread_notifs'      => Notification::where('user_id', $user->id)->unread()->count(),
            ];

            // Chart data: complaints per day for last 14 days
            $complaintsChart = $this->complaintsLast14Days();

            // Waste category distribution
            $categoryData = WasteCategory::withCount('complaints')->get();

            // Sanitization completion by area this week
            $sanitizationChart = $this->sanitizationByArea();

            $recentComplaints = Complaint::with(['user', 'wasteCategory'])
                ->latest()->limit(10)->get();

            $fullBins = Bin::needsCollection()->get();

            return view('admin.dashboard', compact(
                'stats', 'complaintsChart', 'categoryData',
                'sanitizationChart', 'recentComplaints', 'fullBins'
            ));
        }

        // ── Staff dashboard ───────────────────────────────────────────────────
        if ($user->isStaff()) {
            $stats = [
                'assigned_tasks'     => SanitizationTask::where('assigned_to', $user->id)->count(),
                'tasks_today'        => SanitizationTask::where('assigned_to', $user->id)->today()->count(),
                'completed_tasks'    => SanitizationTask::where('assigned_to', $user->id)->completed()->count(),
                'assigned_complaints'=> Complaint::where('assigned_to', $user->id)->count(),
                'unread_notifs'      => Notification::where('user_id', $user->id)->unread()->count(),
            ];

            $myTasks = SanitizationTask::where('assigned_to', $user->id)
                ->today()->orderBy('scheduled_time')->get();

            $myComplaints = Complaint::where('assigned_to', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->with('wasteCategory')->latest()->get();

            return view('staff.dashboard', compact('stats', 'myTasks', 'myComplaints'));
        }

        // ── Public user dashboard ─────────────────────────────────────────────
        $stats = [
            'my_complaints'  => Complaint::where('user_id', $user->id)->count(),
            'pending'        => Complaint::where('user_id', $user->id)->pending()->count(),
            'resolved'       => Complaint::where('user_id', $user->id)->resolved()->count(),
            'my_points'      => $user->points,
            'rank'           => $user->leaderboard_rank,
            'unread_notifs'  => Notification::where('user_id', $user->id)->unread()->count(),
        ];

        $myComplaints = Complaint::where('user_id', $user->id)
            ->with('wasteCategory')->latest()->limit(5)->get();

        $leaderboard = User::orderByDesc('points')->limit(5)->get();

        return view('user.dashboard', compact('stats', 'myComplaints', 'leaderboard'));
    }

    // ── Chart helpers ─────────────────────────────────────────────────────────

    private function complaintsLast14Days(): array
    {
        $data = [];
        for ($i = 13; $i >= 0; $i--) {
            $date  = now()->subDays($i);
            $data[] = [
                'date'  => $date->format('d M'),
                'count' => Complaint::whereDate('created_at', $date)->count(),
            ];
        }
        return $data;
    }

    private function sanitizationByArea(): array
    {
        $areas = SanitizationTask::select('area')
            ->distinct()->pluck('area');

        return $areas->map(function ($area) {
            $total     = SanitizationTask::where('area', $area)->count() ?: 1;
            $completed = SanitizationTask::where('area', $area)->completed()->count();
            return [
                'area'    => $area,
                'percent' => round(($completed / $total) * 100),
            ];
        })->toArray();
    }
}
