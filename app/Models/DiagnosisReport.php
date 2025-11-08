<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'damage_report_id',
        'mechanic_id',
        'tanggal',
        'temuan',
        'komponen_diganti',
        'rencana_tindakan'
    ];
    protected $casts = [
        'tanggal' => 'date',
        'komponen_diganti' => 'array'
    ];

    public function damageReport()
    {
        return $this->belongsTo(\App\Models\LaporanKerusakan::class, 'damage_report_id');
    }
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
    public function requests()
    {
        return $this->hasMany(SparepartRequest::class);
    }
}
