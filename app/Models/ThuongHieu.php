<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThuongHieu extends Model
{
    protected $table = 'thuonghieu';

    public function SanPham(): HasMany{
    return $this->hasMany(SanPham::class, 'thuonghieu_id', 'id');
    }
}
