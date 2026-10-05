<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemController extends Controller
{
    public function settings()
    {
        $settings = [
            'similarity_threshold' => '0.50',
            'confidence_threshold' => '0.50',
            'refresh_seconds' => '15',
        ];

        if (Schema::hasTable('meal_settings')) {
            $stored = DB::table('meal_settings')->pluck('value', 'key')->toArray();
            $settings = array_merge($settings, $stored);
        }

        return view('system.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'similarity_threshold' => 'required|numeric|min:0|max:1',
            'confidence_threshold' => 'required|numeric|min:0|max:1',
            'refresh_seconds' => 'required|integer|min:5|max:300',
        ]);

        if (!Schema::hasTable('meal_settings')) {
            return back()->with('info', 'Table meal_settings belum tersedia. Jalankan migration terlebih dahulu.');
        }

        foreach (['similarity_threshold', 'confidence_threshold', 'refresh_seconds'] as $key) {
            DB::table('meal_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => (string) $request->{$key}, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return back()->with('success', 'Settings berhasil diperbarui.');
    }

    public function users()
    {
        $columns = ['id', 'name'];
        if (Schema::hasColumn('users', 'email')) $columns[] = 'email';

        $users = DB::table('users')->select($columns)->orderBy('name')->limit(500)->get()->map(function ($user) {
            if (!property_exists($user, 'email')) $user->email = null;
            return $user;
        });

        return view('system.users', compact('users'));
    }
}
