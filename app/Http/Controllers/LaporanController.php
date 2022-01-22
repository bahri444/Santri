<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Ruangan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = [
            'title' => 'laporan pembayaran',
            'bulan' => $this->bulan(),
            'tahun' => Pembayaran::selectRaw('DISTINCT(YEAR(created_at)) as tahun')->orderBy('created_at', 'DESC')->get(),
            'ruangan' => Ruangan::all(),
            'urlDatatable' => route('laporan.datatable'),
            'minggu' => $this->calender()
        ];
        return view('admin.laporan.index', $data);
    }
    public function datatable(Request $request)
    {
        $data = Santri::select('santri.id', 'santri.nis', 'santri.nama', 'ruangan.nama_ruangan')
            ->leftJoin('pembayaran', 'santri.id', '=', 'pembayaran.santri_id')
            ->leftJoin('ruangan_santri', 'santri.id', '=', 'ruangan_santri.santri_id')
            ->leftJoin('ruangan', 'ruangan_santri.ruangan_id', '=', 'ruangan.id')
            ->when($request->bulan, function ($query) use ($request) {
                $query->whereRaw("MONTH(pembayaran.tanggal_bayar) = $request->bulan");
            })
            ->when($request->tahun, function ($query) use ($request) {
                $query->whereRaw("YEAR(pembayaran.tanggal_bayar) = $request->tahun");
            })
            ->when($request->ruangan_id, function ($query) use ($request) {
                $query->where('ruangan_santri.ruangan_id', $request->ruangan_id);
            })
            ->groupBy('santri.id', 'santri.nis', 'santri.nama', 'ruangan.nama_ruangan')
            ->get();
        return  DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('minggu', function ($row) {
                return Pembayaran::whereSantri_id($row->id)->whereRaw('YEARWEEK(tanggal_bayar) = YEARWEEK(NOW())')->get()->count();
            })
            ->addColumn('bulan', function ($row) {
                return Pembayaran::whereSantri_id($row->id)->whereRaw('MONTH(tanggal_bayar) = MONTH(NOW())')->whereRaw('YEAR(tanggal_bayar) = YEAR(NOW())')->get()->count();
            })
            ->addColumn('belum', function ($row) {
                $cek = Pembayaran::whereSantri_id($row->id)->whereRaw('MONTH(tanggal_bayar) = MONTH(NOW())')->whereRaw('YEAR(tanggal_bayar) = YEAR(NOW())')->get()->count();
                if (4 - $cek == 0) {
                    return 'Sudah Lunas';
                } else {
                    return (4 - $cek);
                }
            })
            ->addColumn('action', function ($row) {
                $link = '';
                return "<a class='btn btn-info' href='$link' ><i class='fas fa-eye'></i> Detail</a>";
            })
            ->rawColumns(['minggu', 'bulan', 'belum', 'action'])
            ->make(true);
    }
    private function bulan()
    {
        $data = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        return (object)$data;
    }
}
