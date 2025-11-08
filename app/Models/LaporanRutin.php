<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRutin extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranpur_id',
        'reported_by',
        'tanggal',
        'tipe',
        'cond_overall',
        'check_items',
        'ada_temuan_kerusakan',
        'catatan'
    ];
    protected $casts = [
        'check_items' => 'array',
        'tanggal' => 'date',
        'ada_temuan_kerusakan' => 'boolean'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Ranpur::class, 'ranpur_id');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
