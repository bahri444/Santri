<?php

namespace App\Http\Controllers;

use App\Models\AksesMenu;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Ruangan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $data = [
            'title' => 'dashboard',
            'santri' => Santri::all()->count(),
            'ruangan' => Ruangan::all()->count(),
            'minggu' => Pembayaran::whereRaw('YEARWEEK(created_at) = YEARWEEK(NOW())')->get()->count(),
            'bulan' => Pembayaran::whereRaw('YEAR(created_at) = YEAR(NOW())')->whereRaw('MONTH(created_at) = MONTH(NOW())')->get()->count(),
            'totalPengeluaran' => $this->rupiah(Pengeluaran::selectRaw("(SUM(harga) * SUM(jumlah)) as total ")->groupBy(DB::raw('YEARWEEK(tanggal_pembelian)'))->first()->total),
            'totalpengBulan' => $this->rupiah(Pengeluaran::selectRaw("(SUM(harga) * SUM(jumlah)) as total ")->whereMonth('tanggal_pembelian', now())->whereYear('tanggal_pembelian', now())->first()->total),
            'totalPemasukan' => $this->rupiah(Pembayaran::selectRaw("SUM(tagihan.tagihan) as total")->join('tagihan', 'pembayaran.tagihan_id', '=', 'tagihan.id')->groupBy(DB::raw('YEARWEEK(pembayaran.tanggal_bayar)'))->first()->total),
            'totalPemasukanBulan' => $this->rupiah(Pembayaran::selectRaw("SUM(tagihan.tagihan) as total")->join('tagihan', 'pembayaran.tagihan_id', '=', 'tagihan.id')->whereMonth('tanggal_bayar', DB::raw('MONTH(now())'))->whereYear('tanggal_bayar', DB::raw('YEAR(now())'))->first()->total),
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
