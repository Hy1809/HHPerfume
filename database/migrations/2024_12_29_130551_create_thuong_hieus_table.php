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
        Schema::create('thuonghieu', function (Blueprint $table) {
            $table->id();
            $table->string('tenthuonghieu');
            $table->string('tenthuonghieu_slug');
            $table->string('hinhanh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thuonghieu');
    }
};
