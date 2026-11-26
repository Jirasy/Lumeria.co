<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Nomor telepon boleh ditulis dengan spasi, strip, titik, atau kurung; disimpan tanpa pemisah.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->phone)) {
            $this->merge(['phone' => preg_replace('/[\s\-\.\(\)]+/', '', $this->phone)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'      => ['nullable', 'string', 'regex:/^\+?[0-9]{8,15}$/'],
            'gender'     => ['nullable', Rule::in(User::GENDERS)],
            'birth_date' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            // Maksimal 1 MB, hanya JPEG/PNG (SVG sengaja ditolak).
            'avatar'        => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:' . User::AVATAR_MAX_KB],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }
}
