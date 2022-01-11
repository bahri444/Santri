<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use mysqli;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\YearFrac;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use PHPUnit\TextUI\XmlConfiguration\Group;

//Pembayaran::withCount('tgl_bayar')->groupBy('santri_id');

class PembayaranController extends Controller
{
    /**
     * Menampilkan halaman tabel user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Pembayaran::all();
            return DataTables::of($datas)
                ->addIndexColumn()

                ->addColumn('total', function ($row) {
                    return ($row->groupBy('santri_id')->sum('jml_bayar'));
                })
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-santri_id='$row->santri_id' data-jml_bayar='$row->jml_bayar'  data-tgl_bayar='$row->tgl_bayar' class='edit btn btn-sm btn-warning btn-sm' > <i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'> <i class='fas fa-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action', 'total'])
                ->make(true);
        }
        $data = [
            'datatable' => route('pembayaran'),
            'urlTambah' => route('pembayaran.tambah'),
            'urlEdit' => route('pembayaran.edit'),
            'urlHapus' => route('pembayaran.hapus'),

        ];
        return view('admin.pembayaran', $data);
    }
    // public function Total_bayar($row)
    // {
    //     return ($row->sum('jml_bayar'));
    // }
    public function tambah(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'santri_id' => 'required',
            'jml_bayar' => 'required',
            'tgl_bayar' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $pembayaran = Pembayaran::create([
            'santri_id' => $request->santri_id,
            'jml_bayar' => $request->jml_bayar,
            'tgl_bayar' => $request->tgl_bayar,
        ]);
        if ($pembayaran) {
            return response()->json(['pesan' => 'data pembayaran berhasil di tambahkan'], 200);
        } else {
            return response()->json(['pesan' => 'data pembayaran gagal di tambahkan'], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'santri_id' => 'required',
            'jml_bayar' => 'required',
            'tgl_bayar' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $pembayaran = Pembayaran::whereId($request->id)->first();
        if ($pembayaran) {
            $pembayaran->santri_id = $request->santri_id;
            $pembayaran->jml_bayar = $request->jml_bayar;
            $pembayaran->tgl_bayar = $request->tgl_bayar;
            $pembayaran->update();
            return response()->json(['pesan' => 'data histori belanja berhasil di edit'], 200);
        } else {
            return response()->json(['pesan' => 'data histori belanja gagal di edit'], 404);
        }
    }
    public function hapus(Request $request)
    {
        $request = Pembayaran::whereId($request->id)->delete();
        if ($request) {
            return response()->json(['pesan' => 'Data berhasil di hapus'], 200);
        } else {
            return response()->json(['pesan' => 'Data gagal di hapus'], 404);
        }
    }
}
