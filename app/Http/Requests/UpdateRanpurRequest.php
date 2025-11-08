<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRanpurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('ranpur')?->id;

        return [
            'nomor_lambung'   => ['required', 'string', 'max:255', 'unique:ranpurs,nomor_lambung,' . $vehicleId],
            'tipe'            => ['required', 'string', 'max:255'],
            'satuan'          => ['nullable', 'string', 'max:255'],
            'tahun'           => ['nullable', 'digits:4'],
            'status_kesiapan' => ['required', 'in:SIAP LAUT,SIAP DARAT,TIDAK SIAP'],
            'keterangan'      => ['nullable', 'string'],
        ];
    }
}
