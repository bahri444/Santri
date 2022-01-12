<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use Yajra\DataTables\Facades\DataTables;

class RuanganController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ruangan::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $role = $this->role();
                    $btn = '';
                    if ($role->can_edit && $role->can_delete) {
                        $btn = "<button data-id='$row->id' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                        $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    } elseif ($role->can_edit) {
                        $btn = "<button data-id='$row->id' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    } elseif ($role->can_delete) {
                        $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $title = 'Santri';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'Kode Ruangan', 'Nama Ruangan', 'Aksi'];
        else
            $th = ['No', 'Kode Ruangan', 'Nama Ruangan'];
        $urlDatatable = route('ruangan');
        $aksi = route('ruangan.aksi');
        return view('admin.ruangan.index', compact('title', 'th', 'urlDatatable', 'aksi', 'level'));
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
        $santri = Ruangan::create([
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
        ]);
        if ($santri) {
            return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
        }
    }
    private function edit(Request $request)
    {
        $santri = Ruangan::whereId($request->id)->first();
        if ($santri) {
            $santri->kode_ruangan = $request->kode_ruangan;
            $santri->nama_ruangan = $request->nama_ruangan;
            $santri->update();
            return ['status' => 'success', 'pesan' => 'Data berhasil diubah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function hapus(Request $request)
    {
        $santri = Ruangan::whereId($request->id)->first();
        if ($santri) {
            $santri->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    public function detail(Request $request, $id)
    {
        $data = Ruangan::whereId($id)->first();
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 'error', 'pesan' => 'data tidak ditemukan'], 404);
        }
    }
}
