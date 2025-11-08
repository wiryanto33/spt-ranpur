<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreLaporanKerusakanRequest extends FormRequest
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

    /**
     * Role-based validation: crew must have assigned vehicle; force their vehicle_id.
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $v) {
            $user = $this->user();
            if (!$user) return;
            $isPrivileged = $user->hasRole('super-admin') || $user->hasRole('mechanic');
            if (!$isPrivileged) {
                if (empty($user->ranpur_id)) {
                    $v->errors()->add('ranpur_id', 'Anda belum ditugaskan ke ranpur. Hubungi admin.');
                } else {
                    $this->merge(['ranpur_id' => $user->ranpur_id]);
                }
            }
        });
    }
}
