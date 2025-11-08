<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'pangkat' => 'nullable|string|max:255',
            'nrp' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'jabatan' => 'nullable|string|max:255',
            'status' => 'required|in:AKTIF,TIDAK_AKTIF',
            'image' => ['nullable', 'image', 'max:2048'],
            'ranpur_id' => 'nullable|exists:ranpurs,id',
            'role' => 'required|exists:roles,name',
        ];
    }
}
