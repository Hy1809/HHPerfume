<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LoaiSanPham;
use App\Models\ThuongHieu;

class SanPhamController extends Controller
{
    public function getDanhSach()
    {
        $sanpham = SanPham::all();
        return view('sanpham.danhsach', compact('sanpham'));
    }

    public function getThem()
    {
        return view('sanpham.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'loaisanpham_id' => 'required|exists:loaisanpham,id',
            'thuonghieu_id' => 'required|exists:thuonghieu,id',
            'tensanpham' => 'required|string|max:255|unique:sanpham,tensanpham,',
            'dungtichml' => 'required|numeric|min:0',
            'soluong' => 'required|integer|min:1',
            'dongia' => 'required|numeric|min:0',
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $orm = new SanPham();
        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->thuonghieu_id = $request->thuonghieu_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->dungtichml = $request->dungtichml;
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->ghichu = $request->ghichu;
        $path = null;
        if ($request->hasFile('hinhanh')) {
            // Tạo thư mục nếu chưa có
            $lsp = LoaiSanPham::find($request->loaisanpham_id);
            Storage::exists($lsp->tenloai_slug) or Storage::makeDirectory($lsp->tenloai_slug, 0775);

            // Xác định tên tập tin
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;

            // Upload vào thư mục và trả về đường dẫn
            $path = Storage::putFileAs($lsp->tenloai_slug, $request->file('hinhanh'), $filename);
        }
        $orm->hinhanh = $path ?? null;
        $orm->save();

        return redirect()->route('sanpham')->with('success', 'Thêm thành công');
    }

    public function getSua($id)
    {
        $sanpham = SanPham::find($id);
        return view('sanpham.sua', compact('sanpham'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'loaisanpham_id' => 'required|exists:loaisanpham,id',
            'thuonghieu_id' => 'required|exists:thuonghieu,id',
            'tensanpham' => 'required|string|max:255|unique:sanpham,tensanpham,' . $id,
            'dungtichml' => 'required|numeric|min:0',
            'soluong' => 'required|integer|min:1',
            'dongia' => 'required|numeric|min:0',
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Upload hình ảnh
        $path = null;
        if ($request->hasFile('hinhanh')) {
            // Xóa tập tin cũ
            $sp = SanPham::find($id);
            if (!empty($sp->hinhanh)) Storage::delete($sp->hinhanh);

            // Xác định tên tập tin mới
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;

            // Upload vào thư mục và trả về đường dẫn
            $lsp = LoaiSanPham::find($request->loaisanpham_id);
            $path = Storage::putFileAs($lsp->tenloai_slug, $request->file('hinhanh'), $filename);
        }


        // Tìm sản phẩm theo ID
        $orm = SanPham::find($id);

        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->thuonghieu_id = $request->thuonghieu_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->dungtichml = $request->dongia;
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->ghichu = $request->ghichu;
        $orm->hinhanh = $path ?? $orm->hinhanh ?? null;
        $orm->save();
        return redirect()->route('sanpham')->with('success', 'Cập nhật thành công');
    }

    public function getXoa($id)
    {
        $orm = SanPham::find($id);
        $orm->delete();
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
        return redirect()->route('sanpham');
    }
}
