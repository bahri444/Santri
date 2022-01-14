<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Santri;
use App\Models\Pembayaran;
use Yajra\DataTables\Facades\DataTables;

class PembayaranController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('pembayaran.*', 'tagihan.nama_tagihan', 'tagihan.tagihan', 'santri.nis', 'santri.nama')
                ->join('tagihan', 'pembayaran.tagihan_id', '=', 'tagihan.id')
                ->join('santri', 'pembayaran.santri_id', '=', 'santri.id')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pembayaran', function ($row) {
                    return $this->rupiah($row->pembayaran);
                })
                ->addColumn('rupiah', function ($row) {
                    return $this->rupiah($row->tagihan);
                })
                ->addColumn('sisa', function ($row) {
                    return $this->rupiah(0);
                })
                ->addColumn('status', function ($row) {
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $role = $this->role();
                    $btn = '';
                    if ($role->can_edit && $role->can_delete) {
                        $btn = "<button data-id='$row->id' data-nama_tagihan='$row->nama_tagihan' data-tagihan='$row->tagihan' data-keterangan='$row->keterangan' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                        $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    } elseif ($role->can_edit) {
                        $btn = "<button data-id='$row->id' data-nama_tagihan='$row->nama_tagihan' data-tagihan='$row->tagihan' data-keterangan='$row->keterangan'  class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    } elseif ($role->can_delete) {
                        $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'rupiah', 'sisa', 'pembayaran', 'status'])
                ->make(true);
        }
        $title = 'pembayaran';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'NIS', 'Nama Santri', 'Tagihan', 'Pembayaran', 'Sisa', 'status', 'Aksi'];
        else
            $th = ['No', 'NIS', 'Nama Santri', 'Tagihan', 'Pembayaran', 'Sisa', 'status',];
        $urlDatatable = route('pembayaran');
        $aksi = route('pembayaran.aksi');
        $tagihan = Tagihan::latest()->get();
        $santri = Santri::all();
        return view('admin.pembayaran.index', compact('title', 'th', 'urlDatatable', 'aksi', 'level', 'tagihan', 'santri'));
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
        $role = Pembayaran::create([
            'nama_tagihan' => $request->nama_tagihan,
            'tagihan' => $request->tagihan,
            'keterangan' => $request->keterangan
        ]);
        if ($role) {
            return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
        }
    }
    private function edit(Request $request)
    {
        $role = Pembayaran::whereId($request->id)->first();
        if ($role) {
            $role->update([
                'nama_tagihan' => $request->nama_tagihan,
                'tagihan' => $request->tagihan,
                'keterangan' => $request->keterangan
            ]);
            return ['status' => 'success', 'pesan' => 'Data berhasil diubah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function hapus(Request $request)
    {
        $role = Pembayaran::whereId($request->id)->first();
        if ($role) {
            $role->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
}
