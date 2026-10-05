<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function api()
    {
        try {
            $today = Carbon::today();
            $yesterday = $today->copy()->subDay();
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;

            $todayRows = DB::table('attendance_logs')
                ->where('statusenabled', true)
                ->whereDate('attendance_date', $today->toDateString())
                ->select(
                    'id', 'nik', 'visitor_name', 'meal_type', 'quantity', 'order_type',
                    'rating', 'remarks', 'position', 'attendance_date', 'attendance_time',
                    'similarity_score', 'confidence_score', 'is_real_face', 'photo_path'
                )
                ->orderByDesc('attendance_time')
                ->get();

            $todayTotal = (int) $todayRows->sum('quantity');
            $yesterdayTotal = (int) DB::table('attendance_logs')
                ->where('statusenabled', true)
                ->whereDate('attendance_date', $yesterday->toDateString())
                ->sum('quantity');

            $kpi = [
                'today' => [
                    'total' => $todayTotal,
                    'breakfast' => (int) $todayRows->where('meal_type', 'breakfast')->sum('quantity'),
                    'lunch' => (int) $todayRows->where('meal_type', 'lunch')->sum('quantity'),
                    'dinner' => (int) $todayRows->where('meal_type', 'dinner')->sum('quantity'),
                ],
                'month' => [
                    'total' => (int) DB::table('attendance_logs')->where('statusenabled', true)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->sum('quantity'),
                ],
                'comparison' => [
                    'yesterday_total' => $yesterdayTotal,
                    'pct' => $yesterdayTotal > 0 ? round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100, 1) : null,
                ],
            ];

            $trendRows = DB::table('attendance_logs')
                ->where('statusenabled', true)
                ->whereBetween('attendance_date', [Carbon::now()->subDays(6)->toDateString(), Carbon::today()->toDateString()])
                ->select('attendance_date', 'meal_type', 'quantity')
                ->get();

            $trend = $trendRows->groupBy(fn ($r) => Carbon::parse($r->attendance_date)->toDateString())
                ->map(function (Collection $group, $date) {
                    return (object) [
                        'attendance_date' => $date,
                        'breakfast' => (int) $group->where('meal_type', 'breakfast')->sum('quantity'),
                        'lunch' => (int) $group->where('meal_type', 'lunch')->sum('quantity'),
                        'dinner' => (int) $group->where('meal_type', 'dinner')->sum('quantity'),
                    ];
                })->sortBy('attendance_date')->values();

            $hourly = $todayRows->groupBy(function ($r) {
                return Carbon::parse($r->attendance_time ?? $r->attendance_date)->format('H');
            })->map(fn (Collection $g, $hour) => (object) [
                'hour' => (int) $hour,
                'total' => (int) $g->sum('quantity'),
            ])->sortBy('hour')->values();

            $rating = DB::table('attendance_logs')
                ->where('statusenabled', true)
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->where('rating', '>', 0)
                ->avg('rating');

            $similarityThreshold = 0.60;
            $confidenceThreshold = 0.75;
            if (Schema::hasTable('meal_settings')) {
                $settings = DB::table('meal_settings')->pluck('value', 'key');
                $similarityThreshold = (float) ($settings['similarity_threshold'] ?? $similarityThreshold);
                $confidenceThreshold = (float) ($settings['confidence_threshold'] ?? $confidenceThreshold);
            }

            $lowConfidence = $todayRows->filter(function ($r) use ($similarityThreshold, $confidenceThreshold) {
                $simBad = !is_null($r->similarity_score) && (float) $r->similarity_score < $similarityThreshold;
                $confBad = !is_null($r->confidence_score) && (float) $r->confidence_score < $confidenceThreshold;
                return $simBad || $confBad;
            });
            $nonReal = $todayRows->filter(fn ($r) => (int) ($r->is_real_face ?? 1) === 0);
            $qtyAnomaly = $todayRows->filter(fn ($r) => (int) ($r->quantity ?? 0) > 1);
            $duplicates = $todayRows->filter(fn ($r) => !empty($r->nik))
                ->groupBy(fn ($r) => strtoupper($r->nik) . '|' . strtolower($r->meal_type ?? ''))
                ->filter(fn ($g) => $g->count() > 1);

            $orderTypes = $todayRows->groupBy(fn ($r) => $r->order_type ?: 'Unspecified')
                ->map(fn (Collection $g, $name) => ['name' => $name, 'total' => (int) $g->sum('quantity')])
                ->sortByDesc('total')->values();

            $recentRatings = $todayRows->filter(fn ($r) => (float) ($r->rating ?? 0) > 0)
                ->sortByDesc('attendance_time')->take(6)->values();

            return response()->json([
                'kpi' => $kpi,
                'trend' => $trend,
                'hourly' => $hourly,
                'rating' => $rating ? round((float) $rating, 1) : 0,
                'order_types' => $orderTypes,
                'live_activity' => $todayRows->take(8)->values(),
                'alerts' => [
                    'total' => $lowConfidence->count() + $nonReal->count() + $qtyAnomaly->count() + $duplicates->count(),
                    'low_confidence' => $lowConfidence->take(5)->values(),
                    'duplicate_count' => $duplicates->count(),
                    'non_real_count' => $nonReal->count(),
                    'qty_anomaly_count' => $qtyAnomaly->count(),
                ],
                'recent_ratings' => $recentRatings,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
