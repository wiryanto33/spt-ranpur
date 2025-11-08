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
        Schema::create('laporan_rutins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranpur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tanggal');
            $table->enum('tipe', ['RUTIN'])->default('RUTIN');
            $table->enum('cond_overall', ['BAIK', 'CUKUP', 'BURUK'])->default('BAIK');
            $table->json('check_items')->nullable(); // oli, rem, hidrolik, dsb (array)
            $table->boolean('ada_temuan_kerusakan')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['ranpur_id', 'tanggal']); // 1 laporan per hari per kendaraan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_rutins');
    }
};
