<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranpur_id',
        'reported_by',
        'tanggal',
        'judul',
        'deskripsi',
        'status'
    ];
    protected $casts = ['tanggal' => 'date'];

    public function vehicle()
    {
        return $this->belongsTo(Ranpur::class, 'ranpur_id');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    public function diagnosis()
    {
        return $this->hasOne(DiagnosisReport::class);
    }
    public function repairRecords()
    {
        return $this->hasMany(RepairRecord::class);
    }
}
