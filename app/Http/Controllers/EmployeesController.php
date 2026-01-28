<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data = Employees::where(function ($q) {
            $q->where('nik', 'like', '%ABM%')
            ->orWhere('nik', 'like', '%SM%')
            ->orWhere('nik', 'like', '%KJM%')
            ->orWhere('nik', 'like', '%S');
        })
        ->where('statusenabled', true)
        ->selectRaw("
            nik,
            name,
            CASE
                WHEN statusenabled = 1 THEN 'Active'
                ELSE 'Inactive'
            END as statusenabled,
            COALESCE(room, '') as room
        ")
        ->get();
        // 🔍 Search NIK / Name
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data = $data->filter(function ($row) use ($search) {
                return str_contains(strtolower($row->nik), $search)
                    || str_contains(strtolower($row->name), $search);
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

}
