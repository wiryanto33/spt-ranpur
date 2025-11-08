<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiagnosisReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'damage_report_id' => [
                'required',
                'exists:laporan_kerusakans,id',
                Rule::unique('diagnosis_reports', 'damage_report_id'), // one diagnosis per damage report
            ],
            'tanggal' => ['required', 'date'],
            'temuan' => ['required', 'string'],
            'komponen_diganti' => ['nullable', 'array'],
            'komponen_diganti.*' => ['string'],
            'rencana_tindakan' => ['nullable', 'string'],
        ];
    }
}
