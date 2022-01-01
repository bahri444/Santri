<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::all()->count();
        $santri = Santri::all()->count();
        $ruangan = Ruangan::all()->count();
        return view('admin.dashboard', compact('user', 'santri', 'ruangan'));
    }
}
