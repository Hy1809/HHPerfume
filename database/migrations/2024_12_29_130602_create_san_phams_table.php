<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sanpham', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loaisanpham_id')->constrained('loaisanpham');
            $table->foreignId('thuonghieu_id')->constrained('thuonghieu');
            $table->string('tensanpham');
            $table->string('tensanpham_slug');
            $table->double('dungtichml');
            $table->integer('soluong');
            $table->double('dongia');
            $table->string('hinhanh')->nullable();
            $table->text('ghichu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanpham');
    }
};
