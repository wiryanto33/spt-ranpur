<?php

namespace Database\Seeders;

use App\Models\Ranpur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RanpurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ranpur::create([
            'nomor_lambung' => '001',
            'tipe' => 'Tank M1 Abrams',
            'satuan' => 'Batalyon Tank 1',
            'tahun' => 2015,
            'status_kesiapan' => 'SIAP LAUT',
            'keterangan' => 'Tank utama tempur'
        ]);
    }
}
