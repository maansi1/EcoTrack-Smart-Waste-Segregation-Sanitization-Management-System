<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\Report;
use App\Models\SanitizationTask;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('generatedBy')->latest()->paginate(15);

        // Analytics summary
        $stats = [
            'total_complaints'    => Complaint::count(),
            'resolution_rate'     => Complaint::count() > 0
                ? round((Complaint::resolved()->count() / Complaint::count()) * 100) : 0,
            'avg_rating'          => round(Feedback::avg('rating') ?? 0, 1),
            'sanitization_rate'   => SanitizationTask::count() > 0
                ? round((SanitizationTask::completed()->count() / SanitizationTask::count()) * 100) : 0,
        ];

        // Waste by category
        $wasteByCategory = WasteCategory::withCount('complaints')->get();

        // Monthly complaints (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => Complaint::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }

        // Staff performance
        $staffPerformance = User::staff()->active()->withCount([
            'sanitizationTasks as tasks_total',
            'sanitizationTasks as tasks_done' => fn($q) => $q->where('status', 'completed'),
        ])->get();

        return view('admin.reports.index', compact(
            'reports', 'stats', 'wasteByCategory', 'monthlyData', 'staffPerformance'
        ));
    }

    /**
     * Generate a report and store it.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:200',
            'type'      => 'required|in:waste,cleanliness,staff,sanitization,custom',
            'period'    => 'required|in:daily,weekly,monthly,yearly,custom',
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
        ]);

        // Build report data
        $data = $this->buildReportData($validated['type'], $validated['from_date'] ?? null, $validated['to_date'] ?? null);

        $report = Report::create(array_merge($validated, [
            'generated_by' => Auth::id(),
            'data'         => $data,
        ]));

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Report generated successfully.');
    }

    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Export as CSV.
     */
    public function exportCsv(Report $report)
    {
        $data = $report->data;
        $filename = str_slug($report->title) . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, is_array($row) ? $row : [$row]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildReportData(string $type, ?string $from, ?string $to): array
    {
        $query = Complaint::query();
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        if ($type === 'waste') {
            return [
                'total'      => $query->count(),
                'pending'    => (clone $query)->pending()->count(),
                'resolved'   => (clone $query)->resolved()->count(),
                'by_category'=> WasteCategory::withCount(['complaints' => fn($q) => $from || $to ? $q : $q])->get()
                                    ->pluck('complaints_count', 'name')->toArray(),
            ];
        }

        if ($type === 'staff') {
            return User::staff()->active()->withCount([
                'sanitizationTasks as total_tasks',
                'sanitizationTasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
            ])->get()->map(fn($u) => [
                'name'           => $u->name,
                'total_tasks'    => $u->total_tasks,
                'completed_tasks'=> $u->completed_tasks,
            ])->toArray();
        }

        return ['generated_at' => now()->toDateTimeString()];
    }
}
