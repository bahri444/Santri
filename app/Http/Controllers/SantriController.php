<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Santri;
use Illuminate\Support\Facades\File;

class SantriController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Santri::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('ttl', function ($row) {
                    return $row->tempat_lahir . ', ' . $row->tanggal_lahir;
                })
                ->addColumn('jk', function ($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->addColumn('foto', function ($row) {
                    $img = "<img src=" . asset('assets/foto') . "/" . $row->foto . " alt='foto' width='100px' >";
                    return $img;
                })
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
                ->rawColumns(['action', 'ttl', 'jk', 'foto'])
                ->make(true);
        }
        $title = 'Santri';
        $th = [];
        $level = $this->role();
        if ($level->can_edit || $level->can_delete)
            $th = ['No', 'NIS', 'Nama Santri', 'Alamat', 'TTL', 'Jenis Kelamin', 'Foto', 'Aksi'];
        else
            $th = ['No', 'NIS', 'Nama Santri', 'Alamat', 'TTL', 'Jenis Kelamin', 'Foto'];
        $urlDatatable = route('santri');
        $aksi = route('santri.aksi');
        return view('admin.santri.index', compact('title', 'th', 'urlDatatable', 'aksi', 'level'));
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
        $santri = Santri::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jk,
            'foto' => $this->upload($request)
        ]);
        if ($santri) {
            return ['status' => 'success', 'pesan' => 'Data berhasil ditambah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data gagal ditambah'];
        }
    }
    private function edit(Request $request)
    {
        $santri = Santri::whereId($request->id)->first();
        if ($santri) {
            $santri->nis = $request->nis;
            $santri->nama = $request->nama;
            $santri->alamat = $request->alamat;
            $santri->tempat_lahir = $request->tempat_lahir;
            $santri->tanggal_lahir = $request->tanggal_lahir;
            $santri->jenis_kelamin = $request->jk;
            if ($request->hasFile('foto') && $santri->foto != 'default.png') {
                $path = "assets/foto/" . $santri->foto;
                File::delete($path);
                $santri->foto = $this->upload($request);
            }
            $santri->update();
            return ['status' => 'success', 'pesan' => 'Data berhasil diubah'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function hapus(Request $request)
    {
        $santri = Santri::whereId($request->id)->first();
        if ($santri) {
            if ($santri->foto != 'default.png') {
                $path = "assets/foto/" . $santri->foto;
                File::delete($path);
            }
            $santri->delete();
            return ['status' => 'success', 'pesan' => 'Data berhasil dihapus'];
        } else {
            return ['status' => 'error', 'pesan' => 'Data tidak ditemukan'];
        }
    }
    private function upload(Request $request)
    {
        if ($request->hasfile('foto')) {
            $filename = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $request->file('foto')->getClientOriginalName());
            $request->file('foto')->move('assets/foto/', $filename);
            return $filename;
        } else {
            return 'default.png';
        }
    }
    public function detail(Request $request, $id)
    {
        $data = Santri::whereId($id)->first();
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 'error', 'pesan' => 'data tidak ditemukan'], 404);
        }
    }
}
