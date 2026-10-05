<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function daily() { return view('reports.daily'); }
    public function monthly() { return view('reports.monthly'); }
    public function employeeHistory() { return view('reports.employee-history'); }

    public function dailyApi(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $rows = $this->baseQuery()
            ->whereDate('attendance_date', $date->toDateString())
            ->orderByDesc('attendance_time')
            ->get();

        return response()->json([
            'date' => $date->toDateString(),
            'summary' => $this->summary($rows),
            'order_types' => $this->groupTotals($rows, 'order_type'),
            'records' => $rows->take(100)->values(),
        ]);
    }

    public function monthlyApi(Request $request)
    {
        $month = $request->filled('month') ? Carbon::parse($request->month . '-01') : Carbon::now()->startOfMonth();
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $rows = $this->baseQuery()->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])->get();

        $daily = $rows->groupBy(fn ($r) => Carbon::parse($r->attendance_date)->toDateString())
            ->map(function (Collection $g, $date) {
                return [
                    'date' => $date,
                    'total' => (int) $g->sum('quantity'),
                    'breakfast' => (int) $g->where('meal_type', 'breakfast')->sum('quantity'),
                    'lunch' => (int) $g->where('meal_type', 'lunch')->sum('quantity'),
                    'dinner' => (int) $g->where('meal_type', 'dinner')->sum('quantity'),
                ];
            })->sortBy('date')->values();

        return response()->json([
            'month' => $month->format('Y-m'),
            'summary' => $this->summary($rows),
            'daily' => $daily,
            'order_types' => $this->groupTotals($rows, 'order_type'),
        ]);
    }

    public function employeeApi(Request $request)
    {
        $nik = trim((string) $request->nik);
        if ($nik === '') return response()->json(['message' => 'NIK is required.'], 422);

        $limit = min(max((int) $request->get('limit', 100), 10), 300);
        $rows = $this->baseQuery()
            ->where('nik', $nik)
            ->orderByDesc('attendance_time')
            ->limit($limit)
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['nik' => $nik, 'summary' => $this->summary(collect()), 'records' => []]);
        }

        return response()->json([
            'nik' => $nik,
            'summary' => $this->summary($rows),
            'order_types' => $this->groupTotals($rows, 'order_type'),
            'records' => $rows,
        ]);
    }

    private function baseQuery()
    {
        return DB::table('attendance_logs')->where('statusenabled', true)->select(
            'id', 'nik', 'visitor_name', 'meal_type', 'quantity', 'order_type', 'rating',
            'remarks', 'position', 'attendance_date', 'attendance_time', 'created_by'
        );
    }

    private function summary(Collection $rows): array
    {
        $rated = $rows->filter(fn ($r) => (float) ($r->rating ?? 0) > 0);
        return [
            'total' => (int) $rows->sum('quantity'),
            'transactions' => $rows->count(),
            'breakfast' => (int) $rows->where('meal_type', 'breakfast')->sum('quantity'),
            'lunch' => (int) $rows->where('meal_type', 'lunch')->sum('quantity'),
            'dinner' => (int) $rows->where('meal_type', 'dinner')->sum('quantity'),
            'average_rating' => $rated->count() ? round((float) $rated->avg('rating'), 2) : 0,
        ];
    }

    private function groupTotals(Collection $rows, string $field): array
    {
        return $rows->groupBy(fn ($r) => $r->{$field} ?: 'Unspecified')
            ->map(fn (Collection $g, $name) => ['name' => $name, 'total' => (int) $g->sum('quantity')])
            ->sortByDesc('total')->values()->all();
    }
}
