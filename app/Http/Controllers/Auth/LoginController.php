<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        $credentials = ['name' => $request->input('name'), 'password' => $request->input('password')];

        if (Auth::attempt($credentials)) {
            Session::flash('success', 'Đăng nhập thành công!');
            return redirect()->route('oneship.index');
        }

        return redirect()->route('login')->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
