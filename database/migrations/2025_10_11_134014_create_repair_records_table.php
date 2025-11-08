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
        Schema::create('repair_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranpur_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('damage_report_id')->nullable()->constrained('laporan_kerusakans')->nullOnDelete();
            $table->foreignId('mechanic_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('mulai');
            $table->dateTime('selesai')->nullable();
            $table->enum('hasil', ['SIAP', 'TIDAK_SIAP'])->default('SIAP');
            $table->text('uraian_pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_records');
    }
};
