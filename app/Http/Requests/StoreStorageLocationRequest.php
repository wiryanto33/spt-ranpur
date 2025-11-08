<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStorageLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:255', 'unique:storage_locations,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:storage_locations,id'],
        ];
    }
}

