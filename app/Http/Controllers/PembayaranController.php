<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Santri;
use App\Models\Pembayaran;
use Yajra\DataTables\Facades\DataTables;

//Pembayaran::withCount('tgl_bayar')->groupBy('santri_id');

class PembayaranController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('pembayaran.*', 'tagihan.nama_tagihan', 'tagihan.tagihan', 'santri.nis', 'santri.nama', 'ruangan.nama_ruangan')
                ->join('tagihan', 'pembayaran.tagihan_id', '=', 'tagihan.id')
                ->join('santri', 'pembayaran.santri_id', '=', 'santri.id')
                ->join('ruangan_santri', 'santri.id', '=', 'ruangan_santri.santri_id')
                ->join('ruangan', 'ruangan_santri.ruangan_id', '=', 'ruangan.id')
                ->orderBy('created_at', 'DESC')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rupiah', function ($row) {
                    return $this->rupiah($row->tagihan);
                })
<<<<<<< HEAD
                ->addColumn('sisa', function ($row) {
                    return $this->rupiah($row->tagihan - $row->pembayaran);
                })
                ->addColumn('status', function ($row) {
                    if ($row->pembayaran == $row->tagihan) {
                        return 'lunas';
                    } elseif ($row->pembayaran >= $row->tagihan) {
                        return 'pembayaran lebih';
                    } elseif ($row->pembayaran != $row->tagihan) {
                        return 'Tidak';
                    }
                })
=======
>>>>>>> main
                ->addColumn('action', function ($row) {
                    $role = $this->role();
                    $btn = '';
                    if ($role->can_edit && $role->can_delete) {
                        $btn = "<button data-id='$row->id' data-santri_id='$row->santri_id' data-tagihan_id='$row->tagihan_id' data-tanggal_bayar='$row->tanggal_bayar' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                        $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    } elseif ($role->can_edit) {
                        $btn = "<button data-id='$row->id' data-santri_id='$row->santri_id' data-tagihan_id='$row->tagihan_id' data-tanggal_bayar='$row->tanggal_bayar'  class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    } elseif ($role->can_delete) {
                        $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'rupiah'])
                ->make(true);
        }
        $title = 'Pembayaran';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'NIS', 'Nama Santri', 'Pembayaran', 'Tanggal Bayar', 'Ruangan', 'Aksi'];
        else
            $th = ['No', 'NIS', 'Nama Santri', 'Pembayaran', 'Tanggal Bayar', 'Ruangan'];
        $urlDatatable = route('pembayaran');
        $aksi = route('pembayaran.aksi');
        $tagihan = Tagihan::latest()->get();
        $santri = Santri::all();
        return view(
            'admin.pembayaran.index',
            compact('title', 'th', 'urlDatatable', 'aksi', 'level', 'tagihan', 'santri')
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
        $cek = Pembayaran::whereRaw("YEARWEEK(tanggal_bayar) = YEARWEEK('$request->tanggal_bayar')")->whereSantri_id($request->santri_id)->first();
        if ($cek) {
            return ['status' => 'error', 'pesan' => 'Santri sudah membayar uang catering pada Minggu tersebut'];
        } else {
            $role = Pembayaran::create($request->only('santri_id', 'tagihan_id', 'tanggal_bayar'));
            if ($role) {
                return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
            } else {
                return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
            }
        }
    }
    private function edit(Request $request)
    {
        $role = Pembayaran::whereId($request->id)->first();
        if ($role) {
            $role->update($request->only('santri_id', 'tagihan_id', 'tanggal_bayar'));
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
