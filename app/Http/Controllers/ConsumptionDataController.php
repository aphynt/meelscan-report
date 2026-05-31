<?php

namespace App\Http\Controllers;

use App\Exports\ConsumptionExport;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ConsumptionDataController extends Controller
{
    //
    public function index()
    {
        return view('consumptionData.index');
    }

    public function apiConsumption(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page    = (int) $request->get('page', 1);

        $attendance = DB::table('attendance_logs as al')
        ->leftJoin('ref_meals as rm', 'al.food_category', 'rm.id')
            ->select(
                'al.id',
                'al.statusenabled',
                'al.nik',
                'al.visitor_name',
                'al.meal_type',
                'al.quantity',
                'al.remarks',
                'al.order_type',
                'al.created_by',
                'al.rating',
                'rm.item as food_category',
                'al.position',
                'al.is_real_face',
                'al.attendance_date',
                'al.attendance_time',
                'al.photo_path',
            )
            ->where('al.statusenabled', true)
            ->orderByDesc('al.attendance_time')
            ->get();

        $nikList = $attendance
            ->pluck('nik')
            ->filter()
            ->unique()
            ->values();

        $hrData = DB::connection('it')
            ->table('tbl_data_hr')
            ->whereIn('Nik', $nikList)
            ->pluck('Nama', 'Nik');

        $data = $attendance->map(function ($row) use ($hrData) {

            if (!empty($row->visitor_name)) {

                $row->name = $row->visitor_name;
                $row->attendance_type = 'visitor';

            } else {

                $row->name = $hrData[$row->nik] ?? null;
                $row->attendance_type = 'employee';
            }

            return $row;
        });

        $period = trim($request->period ?? '');

        if ($period === '') {
            $today = Carbon::today()->toDateString();

            $data = $data->filter(function ($row) use ($today) {
                return !empty($row->attendance_date)
                    && Carbon::parse($row->attendance_date)->toDateString() === $today;
            });
        }
        elseif (str_contains($period, 'to')) {
            [$start, $end] = array_map('trim', explode('to', $period));

            $startDate = Carbon::parse($start)->startOfDay();
            $endDate   = Carbon::parse($end)->endOfDay();

            $data = $data->filter(function ($row) use ($startDate, $endDate) {
                if (empty($row->attendance_date)) {
                    return false;
                }

                $date = Carbon::parse($row->attendance_date);
                return $date->between($startDate, $endDate);
            });
        }
        else {
            $singleDate = Carbon::parse($period)->toDateString();

            $data = $data->filter(function ($row) use ($singleDate) {
                return !empty($row->attendance_date)
                    && Carbon::parse($row->attendance_date)->toDateString() === $singleDate;
            });
        }

        if ($request->filled('category')) {
            $data = $data->where('meal_type', $request->category);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data = $data->filter(function ($row) use ($search) {

                return str_contains(strtolower($row->nik ?? ''), $search)
                    || str_contains(strtolower($row->name ?? ''), $search)
                    || str_contains(strtolower($row->visitor_name ?? ''), $search);
            });
        }

        $total = $data->count();

        $items = $data
            ->values()
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return response()->json([
            'status' => true,
            'data'   => $items,
            'meta'   => [
                'current_page' => $page,
                'last_page'    => (int) ceil($total / $perPage),
                'per_page'     => $perPage,
                'total'        => $total,
            ]
        ]);
    }

    public function showPhoto($id)
    {
        $photo = AttendanceLog::findOrFail($id);

        return response()->file(storage_path('app/private/' . $photo->photo_path));
    }

    public function destroy($id)
    {
        AttendanceLog::where('id', $id)->update([
            'statusenabled' => 0,
            'deleted_by' => Auth::user()->name,
            ]);

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function addManual(Request $request)
    {
        try {

            if ($request->attendance_type == 'visitor') {

                $nik = strtoupper($request->visitor_nik);
                $visitorName = $request->visitor_name;

            } else {

                $nik = strtoupper($request->nik);
                $visitorName = null;
            }

            AttendanceLog::create([
                'nik'              => $nik,
                'visitor_name'     => $visitorName,
                'meal_type'        => $request->meal_type,
                'quantity'         => $request->quantity,
                'status'           => 'present',
                'created_by'       => Auth::user()->name,
                'order_type'       => $request->order_type,
                'attendance_date'  => $request->attendance_date,
                'food_category'    => (int) $request->food_category,
                'position'         => $request->position,
                'rating'           => 0,
                'attendance_time'  => Carbon::parse($request->attendance_date)
                                            ->setTimeFrom(Carbon::now()),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Manual attendance saved successfully');

        } catch (\Throwable $e) {

            return redirect()->back()
                ->with('info', 'Manual attendance submission failed. ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $attendance = DB::table('attendance_logs as al')
        ->leftJoin('ref_meals as rm', 'al.food_category', 'rm.id')
            ->select(
                'al.id',
                'al.nik',
                'al.visitor_name',
                'al.meal_type',
                'al.status',
                'al.quantity',
                'al.remarks',
                'al.order_type',
                'al.created_by',
                'al.rating',
                'rm.item as food_category',
                'al.position',
                'al.is_real_face',
                'al.attendance_date',
                'al.attendance_time'
            )
            ->where('al.statusenabled', true)
            ->orderByDesc('al.attendance_time')
            ->get();

        $nikList = $attendance->pluck('nik')->unique()->values();

        $hrData = DB::connection('it')
            ->table('tbl_data_hr')
            ->whereIn('Nik', $nikList)
            ->pluck('Nama', 'Nik');

        $data = $attendance->map(function ($row) use ($hrData) {
            $row->name = $hrData[$row->nik] ?? null;
            return $row;
        });

        $period = trim($request->period ?? '');

        if ($period === '') {
            $today = Carbon::today()->toDateString();

            $data = $data->filter(function ($row) use ($today) {
                return !empty($row->attendance_date)
                    && Carbon::parse($row->attendance_date)->toDateString() === $today;
            });
        }
        elseif (str_contains($period, 'to')) {
            [$start, $end] = array_map('trim', explode('to', $period));

            $startDate = Carbon::parse($start)->startOfDay();
            $endDate   = Carbon::parse($end)->endOfDay();

            $data = $data->filter(function ($row) use ($startDate, $endDate) {
                if (empty($row->attendance_date)) {
                    return false;
                }

                $date = Carbon::parse($row->attendance_date);
                return $date->between($startDate, $endDate);
            });
        }
        else {
            $singleDate = Carbon::parse($period)->toDateString();

            $data = $data->filter(function ($row) use ($singleDate) {
                return !empty($row->attendance_date)
                    && Carbon::parse($row->attendance_date)->toDateString() === $singleDate;
            });
        }

        if ($request->filled('category')) {
            $data = $data->where('meal_type', $request->category);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data = $data->filter(function ($row) use ($search) {
                return str_contains(strtolower($row->nik), $search)
                    || str_contains(strtolower($row->name ?? ''), $search);
            });
        }

        return Excel::download(new ConsumptionExport($data->values()),'Consumption Data.xlsx'
    );

    }



}
