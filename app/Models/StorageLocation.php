<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(StorageLocation::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(StorageLocation::class, 'parent_id');
    }
    public function spareparts()
    {
        return $this->hasMany(Sparepart::class);
    }
}
