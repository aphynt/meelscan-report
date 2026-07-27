<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\HealthyMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesController extends Controller
{
    //
    public function index()
    {
        return view('employees.index');
    }

    public function apiEmployees(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page    = (int) $request->get('page', 1);
        $healthyNik = HealthyMenu::pluck('nik')->toArray();

        $data = Employees::where(function ($q) {
            $q->where('nik', 'like', '%ABM%')
            ->orWhere('nik', 'like', '%SM%')
            ->orWhere('nik', 'like', '%KJM%')
            ->orWhere('nik', 'like', '%S%');
            })
            ->where('statusenabled', 1)
            ->orderBy('nik')
            ->get()
            ->map(function ($row) use ($healthyNik) {

                return [
                    'nik'   => $row->nik,
                    'name'  => $row->name,
                    'statusenabled' => $row->statusenabled ? 'Active' : 'Inactive',
                    'room' => $row->room,
                    'healthy' => in_array($row->nik, $healthyNik),
                ];
            });
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data = $data->filter(function ($row) use ($search) {
                return Str::contains(strtolower($row['nik'] ?? ''), $search)
                    || Str::contains(strtolower($row['name'] ?? ''), $search);
            });
        }

        // Pagination manual
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

    public function search(Request $request)
    {
        $q = strtolower($request->q);

        $data = Employees::query()
            ->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(nik) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$q}%"]);
            })
            ->where('statusenabled', true)
            ->limit(20)
            ->get([
                'nik',
                'name'
            ]);

        return response()->json($data);
    }

    public function updateHealthy(Request $request)
    {
        Employees::where('nik', $request->nik)
            ->update([
                'healthy' => $request->healthy
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee Healthy Menu status updated successfully.'
        ]);
    }

    public function employees(Request $request)
    {
        $search = $request->q;

        $employees = Employees::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json(
            $employees->map(function ($e) {
                return [
                    'id' => $e->id,
                    'nik' => $e->nik,
                    'name' => $e->name,
                    'text' => $e->nik.' - '.$e->name
                ];
            })
        );
    }

    public function healthy()
    {
        return HealthyMenu::orderBy('created_at')->get();
    }

    public function setHealthy(Request $request)
    {
        if ($request->type == 'employee') {

            $employee = Employees::where('nik', $request->nik)->first();

            if (!$employee) {
                return response()->json([
                    'message' => 'Employee not found.'
                ], 404);
            }

            $nik = $employee->nik;
            $name = $employee->name;
            $additional = $request->additional;

        } else {

            $request->validate([
                'nik'  => 'required',
                'name' => 'required'
            ]);

            $nik = $request->nik;
            $name = $request->name;
            $additional = $request->additional;
        }

        if (HealthyMenu::where('nik', $nik)->exists()) {
            return response()->json([
                'message' => 'Employee already exists.'
            ], 422);
        }

        HealthyMenu::create([
            'nik'        => $nik,
            'name'       => $name,
            'additional' => $additional
        ]);

        return response()->json([
            'message' => 'Employee added successfully.'
        ]);
    }

    public function removeHealthy(Request $request)
    {
        HealthyMenu::where('nik', $request->nik)->delete();

        return response()->json([
            'message' => 'Employee removed from the Healthy Menu successfully.'
        ]);
    }

}
