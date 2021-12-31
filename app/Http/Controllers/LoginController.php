<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }
    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'username tidak boleh kosong',
            'password.required' => 'password tidak boleh kosong',
        ]);
        $check = $request->only('username', 'password');
        if (Auth::attempt($check)) {
            return redirect()->route('dashboard')->with(['message' => 'selamat datang' . ucfirst(Auth::user()->nama)]);
        } else {
            return back()
                ->with(['message' => 'Username atau password salah'])
                ->withInput(['username' => $request->username]);
        }
        return redirect('login');
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}
