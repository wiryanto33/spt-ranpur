<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSparepartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:255', 'unique:spareparts,kode'],
            'image' => ['nullable', 'image', 'max:2048'],
            'nama' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'storage_location_id' => ['nullable', 'exists:storage_locations,id'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}

