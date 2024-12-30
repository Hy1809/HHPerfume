<?php

namespace App\Http\Controllers;

use App\Models\ThuongHieu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThuongHieuController extends Controller
{
    public function getDanhSach()
    {
        $loaisanpham = LoaiSanPham::all();
        return view('loaisanpham.danhsach', compact('loaisanpham'));
    }

    public function getThem()
    {
        return view('loaisanpham.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tenthuonghieu' => ['required', 'string', 'max:255', 'unique:thuonghieu,tenthuonghieu'],
            'hinhanh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $path = 'null';
        if ($request->hasFile('hinhanh')) {
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tenthuonghieu, '-') . '-' . $extension;
            $path = Storage::putFileAs('thuong-hieu', $request->file('hinhanh'), $filename);
        }


        $orm = new ThuongHieu();
        $orm->tenthuonghieu = $request->tenthuonghieu;
        $orm->tenthuonghieu_slug = Str::slug($request->tenthuonghieu, '-');
        $orm->hinhanh = $path ?? null;
        $orm->save(); // Lưu thông tin vào CSDL
        return redirect()->route('thuonghieu')->with('success', 'Thêm hãng sản xuất thành công');
    }


    public function getSua($id)
    {
        $thuonghieu = ThuongHieu::find($id);
        return view('thuonghieu.sua', compact('thuonghieu'));
    }
    public function postSua(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'tenthuonghieu' => ['required', 'string', 'max:255', 'unique:thuonghieu,tenthuonghieu,' . $id],
            'hinhanh' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $orm = ThuongHieu::find($id);
        $orm->tenthuonghieu = $request->tenthuonghieu;
        $orm->tenthuonghieu_slug = Str::slug($request->tenthuonghieu, '-');

        // upload anh
        $path = 'null';
        if ($request->hasFile('hinhanh')) {
            // Xóa file cũ
            $orm = ThuongHieu::find($id);
            if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
            // upload file moi
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tenthuonghieu, '-') . '.' . $extension;
            $path = Storage::putFileAs('thuong-hieu', $request->file('hinhanh'), $filename);
        }
        $orm->hinhanh = $path ?? $orm->hinhanh ?? null;
        $orm->save();
        return redirect()->route('thuonghieu')->with('success', 'Sửa thành công');
    }

    public function getXoa($id)
    {
        $orm = ThuongHieu::find($id);
        $orm->delete();
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
        return redirect()->route('thuonghieu');
    }
}
