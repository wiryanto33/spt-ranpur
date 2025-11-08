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
        Schema::create('sparepart_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_report_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['DIAJUKAN', 'DISETUJUI', 'DITOLAK', 'SEBAGIAN', 'SELESAI'])->default('DIAJUKAN');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_requests');
    }
};
