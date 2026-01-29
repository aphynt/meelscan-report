<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumptionApiController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page    = (int) $request->get('page', 1);

        $attendance = DB::table('attendance_logs as al')
            ->select(
                'al.id',
                'al.nik',
                'al.meal_type',
                'al.status',
                'al.quantity',
                'al.remarks',
                'al.created_by',
                'al.rating',
                'al.food_category',
                'al.position',
                'al.attendance_date',
                'al.attendance_time'
            )
            ->orderByDesc('al.attendance_time')
            ->get();

        $nikList = $attendance->pluck('nik')->unique();

        $hrData = DB::connection('it')
            ->table('tbl_data_hr')
            ->whereIn('Nik', $nikList)
            ->pluck('Nama', 'Nik');

        $data = $attendance->map(function ($row) use ($hrData) {
            return [
                'id'               => $row->id,
                'nik'              => $row->nik,
                'name'             => $hrData[$row->nik] ?? null,
                'meal_type'        => $row->meal_type,
                'quantity'         => $row->quantity,
                'status'           => $row->status,
                'food_category'    => $row->food_category,
                'position'         => $row->position,
                'attendance_date'  => $row->attendance_date,
                'attendance_time'  => $row->attendance_time,
            ];
        });

        /** FILTER PERIOD */
        $period = trim($request->period ?? '');

        if ($period === '') {
            $today = Carbon::today()->toDateString();
            $data = $data->filter(fn($r) => $r['attendance_date'] === $today);
        }
        elseif (str_contains($period, 'to')) {
            [$start, $end] = array_map('trim', explode('to', $period));
            $startDate = Carbon::parse($start);
            $endDate   = Carbon::parse($end);

            $data = $data->filter(function ($r) use ($startDate, $endDate) {
                return Carbon::parse($r['attendance_date'])
                    ->between($startDate, $endDate);
            });
        }
        else {
            $data = $data->filter(fn($r) => $r['attendance_date'] === $period);
        }

        /** FILTER CATEGORY */
        if ($request->filled('category')) {
            $data = $data->where('meal_type', $request->category);
        }

        /** SEARCH */
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $data = $data->filter(function ($r) use ($search) {
                return str_contains(strtolower($r['nik']), $search)
                    || str_contains(strtolower($r['name'] ?? ''), $search);
            });
        }

        $total = $data->count();

        $items = $data
            ->values()
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Consumption data retrieved',
            'data'    => $items,
            'meta'    => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $total,
                'last_page'    => ceil($total / $perPage),
            ]
        ], 200);
    }
}
