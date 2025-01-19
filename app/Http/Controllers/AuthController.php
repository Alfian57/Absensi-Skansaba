<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login()
    {
        return view('login.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::error('Login Gagal', 'Masih Ada Field Yang Kosong');

            return redirect('/admin/login')->with('email', $request->email);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if ($request->role == 'guru') {
            if (Auth::guard('teacher')->attempt($credentials)) {
                $request->session()->regenerate();
                session()->put('history', []);

                return redirect('/admin/home');
            }
        } else {
            if (Auth::guard('user')->attempt($credentials)) {
                $request->session()->regenerate();
                session()->put('history', []);

                return redirect('/admin/home');
            }
        }

        Alert::error('Login Gagal', 'Akun Tidak Ditemukan');

        return redirect('/admin/login')->with('email', $request->email);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        } else {
            Auth::guard('user')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
