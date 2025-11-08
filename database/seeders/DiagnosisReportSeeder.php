<?php

namespace Database\Seeders;

use App\Models\LaporanKerusakan;
use App\Models\DiagnosisReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiagnosisReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $damage = LaporanKerusakan::first();
        $mechanic = User::first();

        if (!$damage || !$mechanic) {
            return;
        }

        DiagnosisReport::firstOrCreate(
            [
                'damage_report_id' => $damage->id,
            ],
            [
                'mechanic_id' => $mechanic->id,
                'tanggal' => now()->toDateString(),
                'temuan' => 'Seeder: ringkasan diagnosa contoh',
                'komponen_diganti' => ['Rem'],
                'rencana_tindakan' => 'Langkah perbaikan awal',
            ]
        );
    }
}
