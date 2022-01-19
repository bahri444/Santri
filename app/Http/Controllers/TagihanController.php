<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use Yajra\DataTables\Facades\DataTables;

class TagihanController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tagihan::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rupiah', function ($row) {
                    return $this->rupiah($row->tagihan);
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
                ->rawColumns(['action', 'rupiah'])
                ->make(true);
        }
        $title = 'tagihan';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'Nama Tagihan', 'Tagihan', 'Keterangan', 'Aksi'];
        else
            $th = ['No', 'Nama Tagihan', 'Tagihan', 'Keterangan',];
        $urlDatatable = route('tagihan');
        $aksi = route('tagihan.aksi');
        return view('admin.tagihan.index', compact('title', 'th', 'urlDatatable', 'aksi', 'level'));
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
        $role = Tagihan::create([
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
        $role = Tagihan::whereId($request->id)->first();
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
        $role = Tagihan::whereId($request->id)->first();
        if ($role) {
            $role->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
}
