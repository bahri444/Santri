<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class SettingController extends Controller
{
    //
    public function index()
    {
        $data = [
            'title' => 'pengaturan',
            'data' => Setting::whereId(1)->first(),
            'simpan' => route('setting.simpan')
        ];
        return view('admin.setting.index', $data);
    }
    public function simpan(Request $request)
    {
        $setting = Setting::whereId(1)->first();
        if ($setting) {
            $setting->nama = $request->nama;
            $setting->copyright = $request->copyright;
            if ($request->hasFile('logo')) {
                $path = "assets/logo/" . $setting->logo;
                File::delete($path);
                $setting->logo = $this->upload($request);
            }
            $setting->update();
        }
        return redirect()->back();
    }
    private function upload(Request $request)
    {
        $filename = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $request->file('logo')->getClientOriginalName());
        $request->file('logo')->move('assets/logo/', $filename);
        return $filename;
    }
}
