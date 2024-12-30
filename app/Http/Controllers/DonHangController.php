<?php

namespace App\Http\Controllers;

use App\Models\DongHang;
use App\Models\DonHang_ChiTiet;
use App\Models\TinhTrang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonHangController extends Controller
{
    public function getDanhSach()
    {
        $donhang = DonHang::orderBy("created_at", "desc")->get();
        return view('donhang.danhsach', compact('donhang'));
    }
    public function getThem() {}
    public function postThem(Request $request) {}
    public function getSua($id)
    {
        $donhang = DonHang::find($id);
        $tinhtrang = TinhTrang::all();
        return view('donhang.sua', compact('donhang', 'tinhtrang'));
    }
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang_id' => ['required'],
            'sdtgiaohang' => ['required', 'string', 'max:20'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
        ]);

        $orm = DonHang::find($id);
        $orm->tinhtrang_id = $request->tinhtrang_id;
        $orm->sdtgiaohang = $request->sdtgiaohang;
        $orm->diachigiaohang = $request->diachigiaohang;
        $orm->save();
        return redirect()->route('donhang');
    }
    public function getXoa($id)
    {
        DonHang_ChiTiet::where('donhang_id', $id)->delete();
        $orm = DonHang::find($id);
        $orm->delete();
        return redirect('donhang')->route('donhang');
    }
}
