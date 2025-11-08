<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // No-op: Foreign keys are declared within the create_* migrations.
        // This placeholder file previously existed empty and caused a class-not-found error.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to drop since we didn't create additional constraints here.
    }
};
