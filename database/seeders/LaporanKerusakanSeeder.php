<?php

namespace Database\Seeders;

use App\Models\LaporanKerusakan;
use App\Models\User;
use App\Models\Ranpur;
use Illuminate\Database\Seeder;

class LaporanKerusakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicle = Ranpur::first();
        $reporter = User::first();

        if (!$vehicle || !$reporter) {
            return;
        }

        LaporanKerusakan::firstOrCreate(
            [
                'ranpur_id' => $vehicle->id,
                'tanggal' => now()->toDateString(),
                'judul' => 'Contoh kerusakan minor',
            ],
            [
                'reported_by' => $reporter->id,
                'deskripsi' => 'Seeder: kerusakan ringan pada sistem rem',
                'status' => 'DILAPORKAN',
            ]
        );
    }
}
