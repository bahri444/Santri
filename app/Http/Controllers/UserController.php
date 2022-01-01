<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use yajra datatables
use DataTables;
//use models user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Menampilkan halaman tabel user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::all();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<button type='button' data-id='$row->id' data-nama='$row->nama' data-username='$row->username' data-level='$row->level' class='edit btn btn-sm btn-warning btn-sm' > <i class='fas fa-edit'></i></button>";
                    $btn .= "<button type='button' data-id='$row->id' class='hapus btn btn-sm btn-danger m-1'><i class='fas fa-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])   //merender content column dalam bentuk html
                ->make(true);
        }
        $data = [
            'datatable' => route('user'),
            'urlTambah' => route('user.tambah'),
            'urlEdit' => route('user.edit'),
            'urlHapus' => route('user.hapus'),
        ];

        return view('admin.user', $data);
    }
    public function tambah(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required|unique:user',
            'password' => 'required',
            'level' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level
        ]);
        if ($user) {
            return response()->json(['pesan' => 'berhasil menambah data'], 200);
        } else {
            return response()->json(['pesan' => 'gagal menambah data'], 500);
        }
    }
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'level' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }
        $user = User::whereId($request->id)->first();
        if ($user) {
            $user->nama = $request->nama;
            $user->username = $request->username;
            $user->level = $request->level;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->update();
            return response()->json(['pesan' => 'data berhasil di edit'], 200);
        } else {
            return response()->json(['pesan' => 'data gagal di edit'], 404);
        }
    }
    public function delete(Request $request)
    {
        $request = User::whereId($request->id)->delete();
        if ($request) {
            return response()->json(['pesan' => 'data user berhasil di hapus'], 200);
        } else {
            return response()->json(['pesan' => 'data gagal di hapus'], 404);
        }
    }
}
