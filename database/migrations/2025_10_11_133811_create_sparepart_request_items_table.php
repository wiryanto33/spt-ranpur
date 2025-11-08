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
        Schema::create('sparepart_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_request_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('sparepart_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('qty_diminta');
            $table->unsignedInteger('qty_disetujui')->default(0);
            $table->enum('status_item', ['DIAJUKAN', 'DISETUJUI', 'DITOLAK', 'DIPENUHI_SEBAGIAN', 'SELESAI'])->default('DIAJUKAN');
            $table->timestamps();
            $table->unique(['sparepart_request_id', 'sparepart_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_request_items');
    }
};
