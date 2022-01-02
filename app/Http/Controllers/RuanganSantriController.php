<?php

namespace App\Http\Controllers;

use App\Models\RuanganSantri;
use App\Models\Santri;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RuanganSantriController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        if ($request->ajax()) {
            $datas = RuanganSantri::join('ruangan', 'ruangan_santri.ruangan_id', '=', 'ruangan.id')
                ->join('santri', 'ruangan_santri.santri_id', '=', 'santri.id')
                ->where('ruangan_santri.ruangan_id', $id)
                ->get();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' class='edit btn btn-sm btn-warning m-1'><i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'> <i class='fas fa-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data = [
            'datatable' => url("admin/ruangan/santri/$id"),
            'urlSantriBaru' => url("admin/ruangan/santri/$id/baru"),
            'urlTambah' => url("admin/ruangan/santri/$id/tambah"),
            'urlEdit' => url("admin/ruangan/santri/$id/edit"),
            'urlHapus' => url("admin/ruangan/santri/$id/hapus"),
            'ruangan_id' => $id
        ];
        return view('admin.ruanganSantri', $data);
    }
    public function baru(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'jk' => 'required',
            'alamat' => 'required',
            'tempat' => 'required',
            'tgl_lahir' => 'required',
            'ruangan_id' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->all()], 401);
        }
        DB::beginTransaction();
        try {
            $santri = new Santri();
            $santri->nama = $request->nama;
            $santri->jk = $request->jk;
            $santri->alamat = $request->alamat;
            $santri->tempat = $request->tempat;
            $santri->tgl_lahir = $request->tgl_lahir;
            $santri->save();
            $data = [
                'ruangan_id' => $request->ruangan_id,
                'santri_id' => $santri->id
            ];
            RuanganSantri::create($data);
            DB::commit();
            return response()->json(['success' => 'Berhasil menambahkan data'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
