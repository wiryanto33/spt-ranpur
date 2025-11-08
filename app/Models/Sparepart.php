<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'image',
        'nama',
        'satuan',
        'stok',
        'stok_minimum',
        'storage_location_id',
        'keterangan'
    ];

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'storage_location_id');
    }
    public function requestItems()
    {
        return $this->hasMany(SparepartRequestItem::class);
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
