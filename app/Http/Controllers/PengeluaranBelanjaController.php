<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBelanja;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengeluaranBelanjaController extends Controller
{
    /**
     * Menampilkan halaman tabel user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = PengeluaranBelanja::all();
            return DataTables::of($datas)
                ->addIndexColumn()

                //untuk mengalikan jumlah item barang dengan harga item barang
                ->addColumn('total', function ($row) {
                    return ($row->jml * $row->harga_item);
                })
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-nm_barang='$row->nm_barang' data-jml='$row->jml'  data-satuan='$row->satuan' data-harga_item='$row->harga_item' class='edit btn btn-sm btn-warning btn-sm' > <i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'> <i class='fas fa-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action', 'total'])
                ->make(true);
        }
        $data = [
            'datatable' => route('pengeluaranBelanja'),
            'urlTambah' => route('pengeluaranBelanja.tambah'),
            'urlEdit' => route('pengeluaranBelanja.edit'),
            'urlHapus' => route('pengeluaranBelanja.hapus'),

        ];
        return view('admin.pengeluaranBelanja', $data);
    }
    public function tambah(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nm_barang' => 'required',
            'jml' => 'required',
            'satuan' => 'required',
            'harga_item' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $pengeluaranBelanja = PengeluaranBelanja::create([
            'nm_barang' => $request->nm_barang,
            'jml' => $request->jml,
            'satuan' => $request->satuan,
            'harga_item' => $request->harga_item,
        ]);
        if ($pengeluaranBelanja) {
            return response()->json(['pesan' => 'data pengeluaran belanja berhasil di tambahkan'], 200);
        } else {
            return response()->json(['pesan' => 'data pengeluaran belanja gagal di tambahkan'], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nm_barang' => 'required',
            'jml' => 'required',
            'satuan' => 'required',
            'harga_item' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $pengeluaranBelanja = PengeluaranBelanja::whereId($request->id)->first();
        if ($pengeluaranBelanja) {
            $pengeluaranBelanja->nm_barang = $request->nm_barang;
            $pengeluaranBelanja->jml = $request->jml;
            $pengeluaranBelanja->satuan = $request->satuan;
            $pengeluaranBelanja->harga_item = $request->harga_item;
            $pengeluaranBelanja->update();
            return response()->json(['pesan' => 'data histori belanja berhasil di edit'], 200);
        } else {
            return response()->json(['pesan' => 'data histori belanja gagal di edit'], 404);
        }
    }
    public function hapus(Request $request)
    {
        $request = PengeluaranBelanja::whereId($request->id)->delete();
        if ($request) {
            return response()->json(['pesan' => 'Data berhasil di hapus'], 200);
        } else {
            return response()->json(['pesan' => 'Data gagal di hapus'], 404);
        }
    }
}
