<?php

namespace Database\Seeders;

use App\Models\LaporanRutin;
use App\Models\User;
use App\Models\Ranpur;
use Illuminate\Database\Seeder;

class LaporanRutinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicle = Ranpur::first();
        $reporter = User::first();

        if (!$vehicle || !$reporter) {
            return; // nothing to seed if dependencies not present
        }

        LaporanRutin::firstOrCreate(
            [
                'ranpur_id' => $vehicle->id,
                'tanggal' => now()->toDateString(),
            ],
            [
                'reported_by' => $reporter->id,
                'tipe' => 'RUTIN',
                'cond_overall' => 'BAIK',
                'check_items' => ['Oli', 'Rem'],
                'ada_temuan_kerusakan' => false,
                'catatan' => 'Seeder: laporan contoh',
            ]
        );
    }
}
