<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'jenis',
        'qty',
        'reference_type',
        'reference_id',
        'performed_by',
        'tanggal',
        'keterangan'
    ];
    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
    public function reference()
    {
        return $this->morphTo();
    }
    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    
}
