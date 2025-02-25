<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function post(Request $request)
    {
        $request->validate([
            'username'  => 'required',
            'password'  => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (FacadesAuth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('success', 'Berhasil Masuk!');
        }

        return redirect()->route('login')->with('failed', 'Username atau Password Salah');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
