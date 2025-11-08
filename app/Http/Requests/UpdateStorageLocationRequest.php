<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStorageLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $location = $this->route('storage_location');
        $id = is_object($location) ? $location->id : $location;

        return [
            'kode' => ['required', 'string', 'max:255', 'unique:storage_locations,kode,' . $id],
            'nama' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:storage_locations,id', 'not_in:' . $id],
        ];
    }
}

