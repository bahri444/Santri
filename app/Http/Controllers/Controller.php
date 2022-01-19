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
    public function minggu()
    {
        $date1 = "20-02-2010";
        $date2 = "20-04-2010";

        // memecah bagian-bagian dari tanggal $date1
        $pecahTgl1 = explode("-", $date1);

        // membaca bagian-bagian dari $date1
        $tgl1 = $pecahTgl1[0];
        $bln1 = $pecahTgl1[1];
        $thn1 = $pecahTgl1[2];

        echo "<p>Tanggal yang merupakan hari minggu adalah:</p>";

        // counter looping
        $i = 0;

        // counter untuk jumlah hari minggu
        $sum = 0;

        do {
            $tanggal = date("d-m-Y", mktime(0, 0, 0, $bln1, $tgl1 + $i, $thn1));

            if (date("w", mktime(0, 0, 0, $bln1, $tgl1 + $i, $thn1)) == 0) {
                $sum++;
                echo $tanggal . "<br>";
            }

            $i++;
        } while ($tanggal != $date2);
        echo "<p>Jumlah hari minggu antara " . $date1 . " s/d " . $date2 . " adalah: " . $sum . "</p>";
    }
}
