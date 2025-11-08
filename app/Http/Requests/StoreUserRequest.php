<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'pangkat' => 'nullable|string|max:255',
            'nrp' => 'nullable|string|max:255|unique:users,nrp',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'required|in:AKTIF,TIDAK_AKTIF',
            'image' => ['nullable', 'image', 'max:2048'], // or 'image|mimes:jpg,jpeg,png' if you handle file uploads
            'ranpur_id' => 'nullable|exists:ranpurs,id',
            'role' => 'required|exists:roles,name',
        ];
    }
}
