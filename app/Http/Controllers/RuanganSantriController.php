<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\RuanganSantri;
use App\Models\Santri;
use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RuanganSantriController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        if ($request->ajax()) {
            $datas = RuanganSantri::select('ruangan_santri.id', 'santri.nama', 'santri.id as id_santri')
                ->join('ruangan', 'ruangan_santri.ruangan_id', '=', 'ruangan.id')
                ->join('santri', 'ruangan_santri.santri_id', '=', 'santri.id')
                ->where('ruangan_santri.ruangan_id', $id)
                ->get();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-id_santri='$row->id_santri' class='edit btn btn-sm btn-warning m-1'><i class='fas fa-edit'></i></button>";
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
            'getSantri' => url("admin/ruangan/santri/$id/getSantri"),
            'ruangan_id' => $id,
            'ruangan' => Ruangan::where('id', '!=', $id)->get(),
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
    public function getSantri()
    {
        $santri = Santri::select('santri.*')->join('ruangan_santri as b', 'santri.id', '=', 'b.santri_id', 'left')
            ->where('b.santri_id', null)
            ->orWhere('santri.id', null)
            ->get();
        return response()->json($santri);
    }
    public function store(Request $request)
    {
        $santri = $request->santri_id;
        $ruangan = $request->ruangan_id;
        DB::beginTransaction();
        try {
            foreach ($santri as $s) {
                $data = [
                    'ruangan_id' => $ruangan,
                    'santri_id' => $s
                ];
                RuanganSantri::create($data);
            }
            DB::commit();
            return response()->json(['pesan' => 'Berhasil menambahkan data'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['pesan' => $e->getMessage()], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required',
            'santri_id' => 'required',
            'ruangan_id' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->all()], 401);
        }
        $santri = RuanganSantri::where('id', $request->id)->first();
        $santri->santri_id = $request->santri_id;
        $santri->ruangan_id = $request->ruangan_id;
        $santri->update();
        return response()->json(['pesan' => 'Berhasil memindahkan santri'], 200);
    }
    public function delete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->all()], 401);
        }
        $santri = RuanganSantri::where('id', $request->id)->first();
        $santri->delete();
        return response()->json(['pesan' => 'Berhasil menghapus data'], 200);
    }
}
