<?php

namespace App\Http\Controllers;

use App\Models\AksesMenu;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Role;
use App\Models\Ruangan;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Cache\TagSet;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $data = [
            'title' => 'dashboard',
            'user' => User::all()->count(),
            'santri' => Santri::all()->count(),
            'ruangan' => Ruangan::all()->count(),
            'tagihan' => Tagihan::get('tagihan')->where('tagihan' == like(Month(now())),
            'pembayaran' => Pembayaran::groupBy('santri_id')->sum('pembayaran'),
            'role' => Role::all()->count(),
        ];
        return view('admin.dashboard', $data);
    }
    private function getData($id_parent)
    {
        $menu = Menu::whereId_parent($id_parent)->orderBy('urutan', 'ASC')->get();
        if ($menu) {
            $result = [];
            foreach ($menu as $m) {
                $list = [
                    'title' => $m->title,
                    'icon' => $m->icon,
                    'link' => $m->link,
                    'data' => $this->getData($m->id)
                ];
                $result[] = $list;
            }
            return $result;
        } else {
            return [];
        }
    }
    public function getMenu()
    {
        $data = AksesMenu::join('menu as m', 'akses_menu.menu_id', '=', 'm.id')
            ->where('akses_menu.role_id', Auth::user()->role_id)
            ->orderBy('m.urutan', 'ASC')
            ->get();
        if ($data) {
            $result = [];
            foreach ($data as $d) {
                $list = [
                    'title' => $d->title,
                    'icon' => $d->icon,
                    'link' => $d->link,
                    'data' => $this->getData($d->id)
                ];
                $result[] = $list;
            }
            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }
}
