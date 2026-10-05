<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterDataController extends Controller
{
    public function mealPlan()
    {
        $plans = Schema::hasTable('meal_plans')
            ? DB::table('meal_plans')->orderByDesc('plan_date')->orderBy('meal_type')->get()
            : collect();

        return view('master.meal-plan', compact('plans'));
    }

    public function storeMealPlan(Request $request)
    {
        $request->validate([
            'plan_date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'planned_portion' => 'required|integer|min:0',
            'prepared_portion' => 'nullable|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        if (!Schema::hasTable('meal_plans')) {
            return back()->with('info', 'Table meal_plans belum tersedia. Jalankan migration terlebih dahulu.');
        }

        DB::table('meal_plans')->updateOrInsert(
            ['plan_date' => $request->plan_date, 'meal_type' => $request->meal_type],
            [
                'planned_portion' => $request->planned_portion,
                'prepared_portion' => $request->prepared_portion ?? 0,
                'unit_cost' => $request->unit_cost ?? 0,
                'notes' => $request->notes,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return back()->with('success', 'Meal plan berhasil disimpan.');
    }

    public function destroyMealPlan($id)
    {
        if (Schema::hasTable('meal_plans')) DB::table('meal_plans')->where('id', $id)->delete();
        return back()->with('success', 'Meal plan berhasil dihapus.');
    }

    public function foodCategory()
    {
        $categories = Schema::hasTable('ref_meals')
            ? DB::table('ref_meals')->select('id', 'item')->orderBy('item')->get()
            : collect();

        return view('master.food-category', compact('categories'));
    }

    public function messLocation()
    {
        $locations = DB::table('attendance_logs')
            ->where('statusenabled', true)
            ->whereNotNull('position')->where('position', '<>', '')
            ->select('position', DB::raw('COUNT(*) as transactions'), DB::raw('SUM(quantity) as portions'))
            ->groupBy('position')->orderByDesc('transactions')->get();

        return view('master.mess-location', compact('locations'));
    }
}
