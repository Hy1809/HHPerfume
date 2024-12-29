<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanPham extends Model
{
    protected $table = 'sanpham';

    public function LoaiSanPham(): BelongsTo{
        return $this->belongsTo(LoaiSanPham::class, 'loaisanpham_id', 'id');
    }
   
    public function ThuongHieu(): BelongsTo{
        return $this->belongsTo(HangSanXuat::class, 'thuonghieu_id', 'id');
    }
   
    public function DonHang_ChiTiet(): HasMany{
        return $this->hasMany(DonHang_ChiTiet::class, 'sanpham_id', 'id');
    }
}
