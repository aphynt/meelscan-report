<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('dashboard.index');
    }

    public function api()
    {
        try {
            $today = Carbon::today();
            $month = Carbon::now()->month;
            $year  = Carbon::now()->year;

            // 1. KPI Data (Tetap sama)
            $kpi = [
                'today' => [
                    'total'     => (int) DB::table('attendance_logs')->whereDate('attendance_date', $today)->sum('quantity'),
                    'breakfast' => (int) DB::table('attendance_logs')->whereDate('attendance_date', $today)->where('meal_type', 'breakfast')->sum('quantity'),
                    'lunch'     => (int) DB::table('attendance_logs')->whereDate('attendance_date', $today)->where('meal_type', 'lunch')->sum('quantity'),
                    'dinner'    => (int) DB::table('attendance_logs')->whereDate('attendance_date', $today)->where('meal_type', 'dinner')->sum('quantity'),
                ],
                'month' => [
                    'total'     => (int) DB::table('attendance_logs')->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->sum('quantity'),
                ]
            ];

            // 2. Trend 7 Hari (Tetap sama)
            $trend = DB::table('attendance_logs')
                ->selectRaw("attendance_date,
                    SUM(CASE WHEN meal_type='breakfast' THEN quantity ELSE 0 END) as breakfast,
                    SUM(CASE WHEN meal_type='lunch' THEN quantity ELSE 0 END) as lunch,
                    SUM(CASE WHEN meal_type='dinner' THEN quantity ELSE 0 END) as dinner")
                ->whereBetween('attendance_date', [Carbon::now()->subDays(6), Carbon::today()])
                ->groupBy('attendance_date')
                ->orderBy('attendance_date')
                ->get();

            // 3. Distribusi Per Jam (Perbaikan sintaks di sini)
           $hourly = DB::table('attendance_logs')
            ->select(
                DB::raw('DATEPART(HOUR, created_at) as hour'),
                DB::raw('SUM(quantity) as total')
            )
            ->whereDate('attendance_date', $today)
            ->groupBy(DB::raw('DATEPART(HOUR, created_at)'))
            ->orderBy('hour', 'ASC')
            ->get();

            $rating = DB::table('attendance_logs')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('rating', '>', 0)
            ->avg('rating');

            return response()->json([
                'kpi'    => $kpi,
                'trend'  => $trend,
                'hourly' => $hourly,
                'rating' => $rating ? round($rating, 1) : 0
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
