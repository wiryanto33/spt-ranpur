<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Helper: drop any FK(s) on column if exist (name-agnostic)
        $dropFk = function (string $table, string $column) {
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$table, $column]
            );
            foreach ($constraints as $c) {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$c->CONSTRAINT_NAME}`");
            }
        };

        // laporan_rutins.ranpur_id -> CASCADE
        $dropFk('laporan_rutins', 'ranpur_id');
        Schema::table('laporan_rutins', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // laporan_kerusakans.ranpur_id -> CASCADE
        $dropFk('laporan_kerusakans', 'ranpur_id');
        Schema::table('laporan_kerusakans', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // repair_records.ranpur_id -> CASCADE
        $dropFk('repair_records', 'ranpur_id');
        Schema::table('repair_records', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // diagnosis_reports.damage_report_id -> CASCADE
        $dropFk('diagnosis_reports', 'damage_report_id');
        Schema::table('diagnosis_reports', function (Blueprint $table) {
            $table->foreign('damage_report_id')->references('id')->on('laporan_kerusakans')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // sparepart_requests.diagnosis_report_id -> CASCADE
        $dropFk('sparepart_requests', 'diagnosis_report_id');
        Schema::table('sparepart_requests', function (Blueprint $table) {
            $table->foreign('diagnosis_report_id')->references('id')->on('diagnosis_reports')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // sparepart_request_items.sparepart_request_id -> CASCADE
        $dropFk('sparepart_request_items', 'sparepart_request_id');
        Schema::table('sparepart_request_items', function (Blueprint $table) {
            $table->foreign('sparepart_request_id')->references('id')->on('sparepart_requests')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // users.ranpur_id -> NULL ON DELETE (do not delete users)
        // Drop existing FK (if any), then create with nullOnDelete
        try { $dropFk('users', 'ranpur_id'); } catch (\Throwable $e) { /* ignore */ }
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        // Helper again
        $dropFk = function (string $table, string $column) {
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$table, $column]
            );
            foreach ($constraints as $c) {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$c->CONSTRAINT_NAME}`");
            }
        };

        // Revert to RESTRICT (original behavior) where applicable
        $dropFk('laporan_rutins', 'ranpur_id');
        Schema::table('laporan_rutins', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->restrictOnDelete();
        });
        $dropFk('laporan_kerusakans', 'ranpur_id');
        Schema::table('laporan_kerusakans', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->restrictOnDelete();
        });
        $dropFk('repair_records', 'ranpur_id');
        Schema::table('repair_records', function (Blueprint $table) {
            $table->foreign('ranpur_id')->references('id')->on('ranpurs')->cascadeOnUpdate()->restrictOnDelete();
        });
        $dropFk('diagnosis_reports', 'damage_report_id');
        Schema::table('diagnosis_reports', function (Blueprint $table) {
            $table->foreign('damage_report_id')->references('id')->on('laporan_kerusakans')->cascadeOnUpdate()->restrictOnDelete();
        });
        $dropFk('sparepart_requests', 'diagnosis_report_id');
        Schema::table('sparepart_requests', function (Blueprint $table) {
            $table->foreign('diagnosis_report_id')->references('id')->on('diagnosis_reports')->cascadeOnUpdate()->restrictOnDelete();
        });
        $dropFk('sparepart_request_items', 'sparepart_request_id');
        Schema::table('sparepart_request_items', function (Blueprint $table) {
            $table->foreign('sparepart_request_id')->references('id')->on('sparepart_requests')->cascadeOnUpdate()->restrictOnDelete();
        });

        $dropFk('users', 'ranpur_id');
    }
};
