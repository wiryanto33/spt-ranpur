<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranpur_id',
        'damage_report_id',
        'mechanic_id',
        'mulai',
        'selesai',
        'hasil',
        'uraian_pekerjaan'
    ];
    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Ranpur::class, 'ranpur_id');
    }
    public function damageReport()
    {
        return $this->belongsTo(\App\Models\LaporanKerusakan::class, 'damage_report_id');
    }
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    // Catatan: keluarnya sparepart karena perbaikan → StockMovement (OUT) dengan reference = RepairRecord
    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    protected static function booted()
    {
        static::deleting(function (self $record) {
            // Clean up polymorphic stock movements referencing this repair record
            $record->stockMovements()->delete();
        });
    }
}
