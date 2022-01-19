<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Ruangan;
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
            'urlDatatable' => route('laporan.datatable')
        ];
        return view('admin.laporan.index', $data);
    }
    public function datatable(Request $request)
    {
        $data = Pembayaran::whereId_parent(0)->orderBy('urutan', 'ASC')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $role = $this->role();
                $btn = '';
                if ($role->can_edit && $role->can_delete) {
                    $btn = "<button data-id='$row->id' data-title='$row->title' data-icon='$row->icon' data-link='$row->link' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                } elseif ($role->can_edit) {
                    $btn = "<button data-id='$row->id' data-title='$row->title' data-icon='$row->icon' data-link='$row->link' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                } elseif ($role->can_delete) {
                    $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                }
                return $btn;
            })
            ->rawColumns(['action', 'submenu'])
            ->make(true);
    }
    private function bulan()
    {
        $data = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        return (object)$data;
    }
}
