<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranpur extends Model
{
    use HasFactory;

    protected $table = 'ranpurs';

    protected $fillable = [
        'nomor_lambung',
        'tipe',
        'satuan',
        'tahun',
        'status_kesiapan',
        'keterangan'
    ];

    public function laporanRutins()
    {
        return $this->hasMany(LaporanRutin::class);
    }
    public function damageReports()
    {
        return $this->hasMany(\App\Models\LaporanKerusakan::class, 'ranpur_id');
    }
    public function repairRecords()
    {
        return $this->hasMany(RepairRecord::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'ranpur_id');
    }
}
