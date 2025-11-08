<?php

namespace Database\Seeders;

use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class StorageLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudang = StorageLocation::firstOrCreate(
            ['kode' => 'GUD-1'],
            ['nama' => 'Gudang Utama']
        );

        $rakA = StorageLocation::firstOrCreate(
            ['kode' => 'GUD-1/RK-A'],
            ['nama' => 'Rak A', 'parent_id' => $gudang->id]
        );

        StorageLocation::firstOrCreate(
            ['kode' => 'GUD-1/RK-A/BIN-01'],
            ['nama' => 'Bin 01', 'parent_id' => $rakA->id]
        );
    }
}

