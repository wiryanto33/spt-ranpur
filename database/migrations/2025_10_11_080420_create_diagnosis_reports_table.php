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
        Schema::create('diagnosis_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_report_id')->unique()->constrained('laporan_kerusakans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mechanic_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tanggal');
            $table->text('temuan'); // ringkasan diagnosa
            $table->json('komponen_diganti')->nullable(); // daftar kandidat komponen
            $table->text('rencana_tindakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_reports');
    }
};
