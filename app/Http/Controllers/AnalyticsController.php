<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function consumption() { return view('analytics.consumption'); }
    public function peakHours() { return view('analytics.peak-hours'); }
    public function orderType() { return view('analytics.order-type'); }
    public function rating() { return view('analytics.rating'); }
    public function faceVerification() { return view('analytics.face-verification'); }

    public function data(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $rows = DB::table('attendance_logs')
            ->where('statusenabled', true)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->select(
                'id', 'nik', 'visitor_name', 'meal_type', 'quantity', 'order_type', 'rating',
                'remarks', 'position', 'attendance_date', 'attendance_time',
                'similarity_score', 'confidence_score', 'is_real_face', 'photo_path'
            )
            ->orderBy('attendance_time')
            ->get();

        $daily = $rows->groupBy(fn ($r) => Carbon::parse($r->attendance_date)->toDateString())
            ->map(function (Collection $group, string $date) {
                return [
                    'date' => $date,
                    'total' => (int) $group->sum('quantity'),
                    'breakfast' => (int) $group->where('meal_type', 'breakfast')->sum('quantity'),
                    'lunch' => (int) $group->where('meal_type', 'lunch')->sum('quantity'),
                    'dinner' => (int) $group->where('meal_type', 'dinner')->sum('quantity'),
                ];
            })->values();

        $hourly = $rows->groupBy(function ($r) {
            return Carbon::parse($r->attendance_time ?? $r->attendance_date)->format('H');
        })->map(fn (Collection $group, $hour) => [
            'hour' => sprintf('%02d:00', (int) $hour),
            'total' => (int) $group->sum('quantity'),
        ])->sortBy('hour')->values();

        $orderTypes = $rows->groupBy(fn ($r) => $r->order_type ?: 'Unspecified')
            ->map(fn (Collection $group, $name) => ['name' => $name, 'total' => (int) $group->sum('quantity')])
            ->sortByDesc('total')->values();

        $mealTypes = collect(['breakfast', 'lunch', 'dinner'])->map(function ($meal) use ($rows) {
            return ['name' => ucfirst($meal), 'total' => (int) $rows->where('meal_type', $meal)->sum('quantity')];
        });

        $rated = $rows->filter(fn ($r) => (float) ($r->rating ?? 0) > 0);
        $ratingDistribution = collect(range(1, 5))->map(function ($star) use ($rated) {
            return ['rating' => $star, 'total' => $rated->filter(fn ($r) => (int) round($r->rating) === $star)->count()];
        });

        $mealOrderMatrix = collect(['breakfast', 'lunch', 'dinner'])->map(function ($meal) use ($rows) {
            $group = $rows->where('meal_type', $meal);
            return [
                'meal' => ucfirst($meal),
                'types' => $group->groupBy(fn ($r) => $r->order_type ?: 'Unspecified')
                    ->map(fn (Collection $g, $name) => ['name' => $name, 'total' => (int) $g->sum('quantity')])
                    ->values(),
            ];
        });

        $faceRows = $rows->filter(function ($r) {
            return !is_null($r->confidence_score) || !is_null($r->similarity_score) || !is_null($r->is_real_face);
        });

        return response()->json([
            'period' => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
            'summary' => [
                'total' => (int) $rows->sum('quantity'),
                'transactions' => $rows->count(),
                'unique_people' => $rows->pluck('nik')->filter()->unique()->count(),
                'average_rating' => $rated->count() ? round((float) $rated->avg('rating'), 2) : 0,
                'rated_count' => $rated->count(),
            ],
            'daily' => $daily,
            'hourly' => $hourly,
            'meal_types' => $mealTypes,
            'order_types' => $orderTypes,
            'meal_order_matrix' => $mealOrderMatrix,
            'rating_distribution' => $ratingDistribution,
            'recent_ratings' => $rated->sortByDesc('attendance_time')->take(30)->values(),
            'face_summary' => [
                'total' => $faceRows->count(),
                'low_confidence' => $faceRows->filter(fn ($r) => !is_null($r->confidence_score) && (float) $r->confidence_score < .75)->count(),
                'low_similarity' => $faceRows->filter(fn ($r) => !is_null($r->similarity_score) && (float) $r->similarity_score < .60)->count(),
                'non_real' => $faceRows->filter(fn ($r) => (int) ($r->is_real_face ?? 1) === 0)->count(),
            ],
            'face_rows' => $faceRows->sortByDesc('attendance_time')->take(100)->values(),
        ]);
    }

    private function resolveRange(Request $request): array
    {
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : Carbon::today()->endOfDay();
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : $to->copy()->subDays(6)->startOfDay();

        if ($from->gt($to)) [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        if ($from->diffInDays($to) > 92) $from = $to->copy()->subDays(92)->startOfDay();

        return [$from, $to];
    }
}
