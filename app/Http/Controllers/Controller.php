<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function role()
    {
        return Role::whereId(Auth::user()->role_id)->first();
    }
    public function rupiah($angka)
    {
        $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
    public function calender($request = null)
    {
        $bulan    = date("m");
        $tahun    = date("Y");
        $jumlahhari = date("t", mktime(0, 0, 0, $bulan, '01', $tahun));
        $minggu = [];
        for ($d = 1; $d <= $jumlahhari; $d++) {
            if (date("w", mktime(0, 0, 0, $bulan, $d, $tahun)) == 0) {
                $minggu[] = date("Y-m-$d");
            }
        }
        return $minggu;
    }
}
