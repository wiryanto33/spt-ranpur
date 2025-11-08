<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaporanKerusakanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ranpur_id' => ['required', 'exists:ranpurs,id'],
            'tanggal' => ['required', 'date'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['DILAPORKAN', 'DIPERIKSA', 'PROSES_PERBAIKAN', 'SELESAI'])],
        ];
    }
}
