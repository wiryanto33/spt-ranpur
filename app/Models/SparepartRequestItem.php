<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_request_id',
        'sparepart_id',
        'qty_diminta',
        'qty_disetujui',
        'status_item'
    ];

    public function request()
    {
        return $this->belongsTo(SparepartRequest::class, 'sparepart_request_id');
    }
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // OUT movements (pemenuhan item) & IN movements (retur) via morph
    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
