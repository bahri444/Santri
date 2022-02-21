<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Yajra\DataTables\Facades\DataTables;

class PengeluaranController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengeluaran::orderBy('created_at', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('harga', function ($row) {
                    return $this->rupiah($row->harga);
                })
                ->addColumn('total', function ($row) {
                    return $this->rupiah($row->jumlah * $row->harga);
                })
                ->addColumn('action', function ($row) {
                    $role = $this->role();
                    $btn = '';
                    $edit = "<button data-id='$row->id' data-jumlah='$row->jumlah' data-nama_barang='$row->nama_barang' data-harga='$row->harga' data-satuan='$row->satuan' data-tanggal_pembelian='$row->tanggal_pembelian' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    if ($role->can_edit && $role->can_delete) {
                        $btn = $edit;
                        $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    } elseif ($role->can_edit) {
                        $btn = $edit;
                    } elseif ($role->can_delete) {
                        $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'total'])
                ->make(true);
        }
        $title = 'pengeluaran';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'Nama Barang', 'Harga', 'Satuan', 'Jumlah', 'Total', 'Tanggal Pembelian', 'Aksi'];
        else
            $th = ['No', 'Nama Barang', 'Harga', 'Satuan', 'Jumlah', 'Total', 'Tanggal Pembelian'];
        $urlDatatable = route('pengeluaran');
        $aksi = route('pengeluaran.aksi');
        return view(
            'admin.pengeluaran.index',
            compact('title', 'th', 'urlDatatable', 'aksi', 'level')
        );
    }
    public function aksi(Request $request)
    {
        $aksi = $request->aksi;
        $data = [];
        switch ($aksi) {
            case 'tambah':
                $data = $this->tambah($request);
                break;
            case 'edit':
                $data = $this->edit($request);
                break;
            case 'hapus':
                $data = $this->hapus($request);
                break;
            default:
                $data = ['status' => 'error', 'status' => 'Aksi tidak ditemukan'];
                break;
        }
        return response()->json($data);
    }
    private function tambah(Request $request)
    {
        $cek = Pengeluaran::create($request->only('nama_barang', 'harga', 'satuan', 'jumlah', 'tanggal_pembelian'));
        if ($cek)
            return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
        else
            return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
    }
    private function edit(Request $request)
    {
        $role = Pengeluaran::whereId($request->id)->first();
        if ($role) {
            $role->update($request->only('nama_barang', 'harga', 'satuan', 'jumlah', 'tanggal_pembelian'));
            return ['status' => 'success', 'pesan' => 'Data berhasil diubah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function hapus(Request $request)
    {
        $role = Pengeluaran::whereId($request->id)->first();
        if ($role) {
            $role->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
}
