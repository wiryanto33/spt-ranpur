<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loc = StorageLocation::first();
        Sparepart::firstOrCreate(
            ['kode' => 'BRG-001'],
            [
                'nama' => 'Contoh Sparepart',
                'satuan' => 'pcs',
                'stok' => 10,
                'stok_minimum' => 2,
                'storage_location_id' => $loc?->id,
                'keterangan' => 'Seeder contoh',
            ]
        );
    }
}

