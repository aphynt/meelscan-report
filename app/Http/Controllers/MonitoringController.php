<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MonitoringController extends Controller
{
    public function index()
    {
        return view('monitoring.index');
    }

    public function api(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)->toDateString()
            : Carbon::today()->toDateString();

        $rows = DB::table('attendance_logs')
            ->select(
                'id', 'nik', 'visitor_name', 'meal_type', 'quantity', 'order_type',
                'rating', 'position', 'attendance_date', 'attendance_time',
                'similarity_score', 'confidence_score', 'is_real_face', 'photo_path',
                'created_by'
            )
            ->where('statusenabled', true)
            ->whereDate('attendance_date', $date)
            ->orderByDesc('attendance_time')
            ->get();

        $similarityThreshold = 0.50;
        $confidenceThreshold = 0.50;
        $refreshSeconds = 15;

        if (Schema::hasTable('meal_settings')) {
            $settings = DB::table('meal_settings')->pluck('value', 'key');
            $similarityThreshold = (float) ($settings['similarity_threshold'] ?? $similarityThreshold);
            $confidenceThreshold = (float) ($settings['confidence_threshold'] ?? $confidenceThreshold);
            $refreshSeconds = (int) ($settings['refresh_seconds'] ?? $refreshSeconds);
        }

        $lowConfidence = $rows->filter(function ($row) use ($similarityThreshold, $confidenceThreshold) {
            $similarity = is_null($row->similarity_score) ? 1 : (float) $row->similarity_score;
            $confidence = is_null($row->confidence_score) ? 1 : (float) $row->confidence_score;
            return $similarity < $similarityThreshold || $confidence < $confidenceThreshold;
        })->values();

        $nonRealFace = $rows->filter(fn ($row) => (int) ($row->is_real_face ?? 1) === 0)->values();
        $qtyAnomaly = $rows->filter(fn ($row) => (int) ($row->quantity ?? 0) > 1)->values();

        $duplicates = $rows
            ->filter(fn ($row) => !empty($row->nik))
            ->groupBy(fn ($row) => strtoupper($row->nik) . '|' . strtolower($row->meal_type ?? ''))
            ->filter(fn ($group) => $group->count() > 1)
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'nik' => $first->nik,
                    'meal_type' => $first->meal_type,
                    'count' => $group->count(),
                    'latest_time' => $group->max('attendance_time'),
                ];
            })->values();

        $orderTypes = $rows->groupBy(fn ($row) => $row->order_type ?: 'Unspecified')
            ->map(fn ($group) => (int) $group->sum('quantity'));

        $mealTypes = $rows->groupBy(fn ($row) => strtolower($row->meal_type ?: 'other'))
            ->map(fn ($group) => (int) $group->sum('quantity'));

        $last15 = $rows->filter(function ($row) {
            if (empty($row->attendance_time)) return false;
            return Carbon::parse($row->attendance_time)->gte(now()->subMinutes(15));
        })->sum('quantity');

        return response()->json([
            'date' => $date,
            'summary' => [
                'total' => (int) $rows->sum('quantity'),
                'transactions' => $rows->count(),
                'last_15_minutes' => (int) $last15,
                'alerts' => $lowConfidence->count() + $nonRealFace->count() + $qtyAnomaly->count() + $duplicates->count(),
            ],
            'meal_types' => $mealTypes,
            'order_types' => $orderTypes,
            'activity' => $rows->take(25)->values(),
            'alerts' => [
                'low_confidence' => $lowConfidence->take(15)->values(),
                'duplicate' => $duplicates->take(15)->values(),
                'non_real_face' => $nonRealFace->take(15)->values(),
                'qty_anomaly' => $qtyAnomaly->take(15)->values(),
            ],
            'thresholds' => [
                'similarity' => $similarityThreshold,
                'confidence' => $confidenceThreshold,
                'refresh_seconds' => $refreshSeconds,
            ],
        ]);
    }
}
