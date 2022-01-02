<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    /**
     * Menampilkan halaman tabel user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Ruangan::all();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('btn', function ($row) {
                    $btn = "<a href='" . url("admin/ruangan/santri/$row->id") . "' class='btn btn-info btn-sm' >Tambah Santri</a>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-ruangan='$row->ruangan' class='edit btn btn-sm btn-warning m-1'><i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'> <i class='fas fa-trash'></i></button>";

                    return $btn;
                })
                ->rawColumns(['action', 'btn'])
                ->make(true);
        }
        $data = [
            'datatable' => route('ruangan'),
            'urlTambah' => route('ruangan.tambah'),
            'urlEdit' => route('ruangan.edit'),
            'urlHapus' => route('ruangan.hapus'),
        ];
        return view('admin.ruangan', $data);
    }
    public function tambah(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ruangan' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $ruangan = Ruangan::create([
            'ruangan' => $request->ruangan,
        ]);
        if ($ruangan) {
            return response()->json(['pesan' => 'data ruangan berhasil di tambahkan'], 200);
        } else {
            return response()->json(['pesan' => 'data ruangan gagal di tambahkan'], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ruangan' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $ruangan = Ruangan::whereId($request->id)->first();
        if ($ruangan) {
            $ruangan->ruangan = $request->ruangan;
            $ruangan->update();
            return response()->json(['pesan' => 'data ruangan berhasil di edit'], 200);
        } else {
            return response()->json(['pesan' => 'data ruangan gagal di edit'], 404);
        }
    }
    public function delete(Request $request)
    {
        $request = Ruangan::whereId($request->id)->delete();
        if ($request) {
            return response()->json(['pesan' => 'Data berhasil dihapus.'], 200);
        } else {
            return response()->json(['pesan' => 'data gagal di hapus.'], 404);
        }
    }
}
