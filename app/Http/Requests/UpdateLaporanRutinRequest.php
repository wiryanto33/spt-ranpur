<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaporanRutinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->input('ranpur_id');
        $id = $this->route('laporan_rutin')?->id;

        return [
            'ranpur_id' => ['required', 'exists:ranpurs,id'],
            'tanggal' => [
                'required',
                'date',
                Rule::unique('laporan_rutins', 'tanggal')->ignore($id)->where(fn ($q) => $q->where('ranpur_id', $vehicleId)),
            ],
            'tipe' => ['required', Rule::in(['RUTIN'])],
            'cond_overall' => ['required', Rule::in(['BAIK', 'CUKUP', 'BURUK'])],
            'check_items' => ['nullable', 'array'],
            'check_items.*' => ['string'],
            'ada_temuan_kerusakan' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ];
    }
}
