<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartRequest extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosis_report_id', 'requested_by', 'approved_by', 'tanggal', 'status', 'catatan'];
    protected $casts = ['tanggal' => 'date'];

    public function diagnosis()
    {
        return $this->belongsTo(DiagnosisReport::class, 'diagnosis_report_id');
    }
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function items()
    {
        return $this->hasMany(SparepartRequestItem::class);
    }
}
