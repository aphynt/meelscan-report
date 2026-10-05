<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorsController extends Controller
{
    public function index()
    {
        return view('visitors.index');
    }

    public function api(Request $request)
    {
        $perPage = min(max((int) $request->get('per_page', 15), 5), 100);

        $query = DB::table('attendance_logs')
            ->where('statusenabled', true)
            ->whereNotNull('visitor_name')
            ->where('visitor_name', '<>', '')
            ->select('id', 'nik', 'visitor_name', 'meal_type', 'quantity', 'order_type', 'position', 'attendance_date', 'attendance_time', 'created_by')
            ->orderByDesc('attendance_time');

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', Carbon::parse($request->date)->toDateString());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate($perPage));
    }
}
