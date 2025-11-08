<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreLaporanRutinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->input('ranpur_id');

        return [
            'ranpur_id' => ['required', 'exists:ranpurs,id'],
            'tanggal' => [
                'required',
                'date',
                Rule::unique('laporan_rutins', 'tanggal')->where(fn ($q) => $q->where('ranpur_id', $vehicleId)),
            ],
            'tipe' => ['required', Rule::in(['RUTIN'])],
            'cond_overall' => ['required', Rule::in(['BAIK', 'CUKUP', 'BURUK'])],
            'check_items' => ['nullable', 'array'],
            'check_items.*' => ['string'],
            'ada_temuan_kerusakan' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    /**
     * Add role-based validation: crew must have assigned vehicle and cannot choose other vehicle.
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
                    // Force ranpur_id to user's assigned vehicle
                    $this->merge(['ranpur_id' => $user->ranpur_id]);
                }
            }
        });
    }
}
