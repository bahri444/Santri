<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RuanganSantri;
use App\Models\Ruangan;
use App\Models\Santri;
use Yajra\DataTables\Facades\DataTables;

class RuanganSantriController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = RuanganSantri::select('ruangan_santri.*', 'santri.nis', 'santri.nama')->join('santri', 'ruangan_santri.santri_id', '=', 'santri.id')->where('ruangan_id', $id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $role = $this->role();
                    $btn = '';
                    if ($role->can_edit && $role->can_delete) {
                        $btn = "<button data-id='$row->id' data-santri_id='$row->santri_id' data-ruangan_id='$row->ruangan_id' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                        $btn .= "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    } elseif ($role->can_edit) {
                        $btn = "<button data-id='$row->id' data-santri_id='$row->santri_id' data-ruangan_id='$row->ruangan_id' class='edit btn btn-warning btn-sm m-1'><i class='fas fa-edit'></i></button>";
                    } elseif ($role->can_delete) {
                        $btn = "<button data-id='$row->id' class='hapus btn btn-danger btn-sm m-1'><i class='fas fa-trash'></i></button>";
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'santri'])
                ->make(true);
        }
        $title = 'ruangan';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'Nis', 'Nama Santri', 'Aksi'];
        else
            $th = ['No', 'Nis', 'Nama Santri'];
        $urlDatatable = route('ruangan.santri', $id);
        $aksi = route('ruangan.santri.aksi', $id);
        $getSantri = route('ruangan.santri.getSantri', $id);
        $getRuangan = route('ruangan.santri.getRuangan', $id);
        return view('admin.ruangan.santri', compact('title', 'th', 'urlDatatable', 'aksi', 'level', 'getSantri', 'getRuangan'));
    }
    public function aksi(Request $request, $id)
    {
        $aksi = $request->aksi;
        $data = [];
        switch ($aksi) {
            case 'tambah':
                $data = $this->tambah($request, $id);
                break;
            case 'edit':
                $data = $this->edit($request, $id);
                break;
            case 'hapus':
                $data = $this->hapus($request, $id);
                break;
            default:
                $data = ['status' => 'error', 'status' => 'Aksi tidak ditemukan'];
                break;
        }
        return response()->json($data);
    }
    private function tambah(Request $request, $id)
    {
        $santri_id = $request->santri_id;
        $insert = [];
        foreach ($santri_id as $s) {
            $insert[] = [
                'ruangan_id' => $id,
                'santri_id' => $s,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $santri = RuanganSantri::insert($insert);
        if ($santri) {
            return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
        }
    }
    private function edit(Request $request, $id)
    {
        $santri = RuanganSantri::whereId($request->id)->first();
        if ($santri) {
            $santri->santri_id = $request->santri_id;
            $santri->ruangan_id = $request->ruangan_id;
            $santri->update();
            return ['status' => 'success', 'pesan' => 'Data berhasil diubah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function hapus(Request $request, $id)
    {
        $santri = RuanganSantri::whereId($request->id)->first();
        if ($santri) {
            $santri->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    public function getRuangan($id)
    {
        $data = Ruangan::where('id', '!=', $id)->get();
        return response()->json($data);
    }
    public function getSantri()
    {
        $santri = RuanganSantri::join('santri', 'ruangan_santri.santri_id', '=', 'santri.id', 'right')->whereRaw('ruangan_santri.id IS NULL')->orWhereRaw('santri.id IS NULL')->get();
        return response()->json($santri);
    }
}
