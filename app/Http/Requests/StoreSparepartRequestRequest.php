<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSparepartRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diagnosis_report_id' => ['required', 'exists:diagnosis_reports,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', Rule::in(['DIAJUKAN', 'DISETUJUI', 'DITOLAK', 'SEBAGIAN', 'SELESAI'])],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sparepart_id' => ['required', 'exists:spareparts,id'],
            'items.*.qty_diminta' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $user = $this->user();
            if (!$user) return;
            $canSetStatus = $user->hasRole('super-admin') || $user->hasRole('staff');
            if (!$canSetStatus) {
                $this->merge(['status' => 'DIAJUKAN']);
            }
        });
    }
}
