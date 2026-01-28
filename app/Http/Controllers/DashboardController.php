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
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        // ===== KPI =====
        $kpi = [
            'today' => [
                'total' => DB::table('attendance_logs')
                    ->whereDate('attendance_date', $today)
                    ->sum('quantity'),

                'breakfast' => DB::table('attendance_logs')
                    ->whereDate('attendance_date', $today)
                    ->where('meal_type', 'breakfast')
                    ->sum('quantity'),

                'lunch' => DB::table('attendance_logs')
                    ->whereDate('attendance_date', $today)
                    ->where('meal_type', 'lunch')
                    ->sum('quantity'),

                'dinner' => DB::table('attendance_logs')
                    ->whereDate('attendance_date', $today)
                    ->where('meal_type', 'dinner')
                    ->sum('quantity'),
            ],

            'month' => [
                'total' => DB::table('attendance_logs')
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                    ->sum('quantity'),
            ]
        ];

        // ===== CHART (7 hari terakhir) =====
        $trend = DB::table('attendance_logs')
            ->selectRaw("
                attendance_date,
                SUM(CASE WHEN meal_type='breakfast' THEN quantity ELSE 0 END) as breakfast,
                SUM(CASE WHEN meal_type='lunch' THEN quantity ELSE 0 END) as lunch,
                SUM(CASE WHEN meal_type='dinner' THEN quantity ELSE 0 END) as dinner
            ")
            ->whereBetween('attendance_date', [
                Carbon::now()->subDays(6),
                Carbon::today()
            ])
            ->groupBy('attendance_date')
            ->orderBy('attendance_date')
            ->get();

        return response()->json([
            'kpi'   => $kpi,
            'trend' => $trend
        ]);
    }
}
