<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    //
    public function index()
    {
        return view('orders.index');
    }

    public function create(Request $request)
    {
        try {
            AttendanceLog::create([
                'nik'             => Auth::user()->nik,
                'meal_type'       => $request->meal_type,
                'status'          => 'present',
                'quantity'        => (int) $request->quantity,
                'remarks_order'   => $request->remarks_order ?? null,
                'created_by'      => 'order',
                'rating'          => 0,
                'attendance_date' => Carbon::parse($request->order_date)->toDateString(),
                'attendance_time' => Carbon::now(),
                'food_category'   => 'basic',
                'position'        => 'Mess IWACO',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            return redirect()->back()->with(
                'success',
                'Food order has been successfully submitted.'
            );

        } catch (\Throwable $e) {

            return redirect()->back()->with(
                'error',
                'Failed to submit food order. Please try again.'
            );
        }
    }

}
