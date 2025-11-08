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
        Schema::create('laporan_kerusakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranpur_id')->constrained('ranpurs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tanggal');
            $table->string('judul'); // ringkas
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['DILAPORKAN', 'DIPERIKSA', 'PROSES_PERBAIKAN', 'SELESAI'])->default('DILAPORKAN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakans');
    }
};
