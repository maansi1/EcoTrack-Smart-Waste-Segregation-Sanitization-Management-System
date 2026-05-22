<?php
// routes/web.php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SanitizationController;
use App\Http\Controllers\WasteGuideController;
use Illuminate\Support\Facades\Route;

// ── Public pages ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    $complaintCount = 0;
    $resolvedCount = 0;
    $binCount = 0;
    $userCount = 0;
    $dbUnavailable = false;

    try {
        $complaintCount = \App\Models\Complaint::count();
        $resolvedCount = \App\Models\Complaint::resolved()->count();
        $binCount = \App\Models\Bin::count();
        $userCount = \App\Models\User::count();
    } catch (\Throwable $e) {
        $dbUnavailable = true;
    }

    return view('public.home', compact(
        'complaintCount',
        'resolvedCount',
        'binCount',
        'userCount',
        'dbUnavailable'
    ));
})->name('home');
Route::get('/about', fn() => view('public.about'))->name('about');
Route::get('/features', fn() => view('public.features'))->name('features');
Route::get('/contact', fn() => view('public.contact'))->name('contact');

// ── Auth (Laravel Breeze) ─────────────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Authenticated routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (role-aware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Waste guide (AI segregation)
    Route::get('/waste-guide', [WasteGuideController::class, 'index'])->name('waste-guide.index');
    Route::post('/waste-guide/guide', [WasteGuideController::class, 'guide'])->name('waste-guide.guide');

    // Smart Bins (view — all roles)
    Route::get('/bins', [BinController::class, 'index'])->name('bins.index');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // ── User & Staff routes ───────────────────────────────────────────────────
    Route::middleware('role:user,staff,admin')->group(function () {

        // Complaints
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/track', [ComplaintController::class, 'track'])->name('complaints.track');
        Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    });

    // ── Staff + Admin routes ──────────────────────────────────────────────────
    Route::middleware('role:staff,admin')->group(function () {

        // Sanitization tasks
        Route::get('/sanitization', [SanitizationController::class, 'index'])->name('sanitization.index');
        Route::post('/sanitization', [SanitizationController::class, 'store'])->name('sanitization.store');
        Route::put('/sanitization/{sanitizationTask}', [SanitizationController::class, 'update'])->name('sanitization.update');
    });

    // ── Admin-only routes ─────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // User management
        Route::resource('users', UserController::class);

        // Complaint management (assign/update status)
        Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
        Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

        // Bin management
        Route::put('/bins/{bin}', [BinController::class, 'update'])->name('bins.update');
        Route::post('/bins/{bin}/collected', [BinController::class, 'markCollected'])->name('bins.collected');
        Route::post('/bins', [BinController::class, 'store'])->name('bins.store');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/csv', [ReportController::class, 'exportCsv'])->name('reports.csv');
    });
});
