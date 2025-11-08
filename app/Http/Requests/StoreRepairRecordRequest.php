<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRepairRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ranpur_id' => ['required', 'exists:ranpurs,id'],
            'damage_report_id' => ['nullable', 'exists:laporan_kerusakans,id'],
            'mulai' => ['required', 'date'],
            'selesai' => ['nullable', 'date', 'after_or_equal:mulai'],
            'hasil' => ['required', Rule::in(['SIAP', 'TIDAK_SIAP'])],
            'uraian_pekerjaan' => ['nullable', 'string'],
        ];
    }
}
