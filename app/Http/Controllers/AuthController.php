<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }

    public function post(Request $request)
    {
        $request->validate([
            'nik'    => 'required',
            'password' => 'required',
        ], [
            'nik.required' => 'Nik wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt([
            'nik' => $request->nik,
            'password' => $request->password,
            'statusenabled' => true,
        ])) {
            $request->session()->regenerate();
            if(Auth::user()->role == 'Admin'){
                return redirect()->route('dashboard');
            }else{
                return redirect()->route('orders');
            }

        }

        return back()->with('info', 'NIK atau password salam');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
