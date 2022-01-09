<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SantriController extends Controller
{
    /**
     * Menampilkan halaman tabel user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Santri::all();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-nama='$row->nama' data-jk='$row->jk'  data-alamat='$row->alamat' data-tempat='$row->tempat' data-tgl_lahir='$row->tgl_lahir' class='edit btn btn-sm btn-warning btn-sm' > <i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'> <i class='fas fa-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data = [
            'datatable' => route('santri'),
            'urlTambah' => route('santri.tambah'),
            'urlEdit' => route('santri.edit'),
            'urlHapus' => route('santri.hapus'),
        ];
        return view('admin.santri', $data);
    }
    public function tambah(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'tempat' => 'required',
            'tgl_lahir' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $santri = Santri::create([
            'nama' => $request->nama,
            'jk' => $request->jk,
            'alamat' => $request->alamat,
            'tempat' => $request->tempat,
            'tgl_lahir' => $request->tgl_lahir
        ]);
        if ($santri) {
            return response()->json(['pesan' => 'data santri berhasil di tambahkan'], 200);
        } else {
            return response()->json(['pesan' => 'data santri gagal di tambahkan'], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'tempat' => 'required',
            'tgl_lahir' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $santri = Santri::whereId($request->id)->first();
        if ($santri) {
            $santri->nama = $request->nama;
            $santri->jk = $request->jk;
            $santri->alamat = $request->alamat;
            $santri->tempat = $request->tempat;
            $santri->tgl_lahir = $request->tgl_lahir;
            $santri->update();
            return response()->json(['pesan' => 'data santri berhasil di edit'], 200);
        } else {
            return response()->json(['pesan' => 'data santri gagal di edit'], 404);
        }
    }
    public function delete(Request $request)
    {
        $request = Santri::whereId($request->id)->delete();
        if ($request) {
            return response()->json(['pesan' => 'Data berhasil di hapus'], 200);
        } else {
            return response()->json(['pesan' => 'Data gagal di hapus'], 404);
        }
    }
}
