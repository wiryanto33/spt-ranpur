<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRanpurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_lambung'   => ['required', 'string', 'max:255', 'unique:ranpurs,nomor_lambung'],
            'tipe'            => ['required', 'string', 'max:255'],
            'satuan'          => ['nullable', 'string', 'max:255'],
            'tahun'           => ['nullable', 'digits:4'],
            'status_kesiapan' => ['required', 'in:SIAP LAUT,SIAP DARAT,TIDAK SIAP'],
            'keterangan'      => ['nullable', 'string'],
        ];
    }
}
