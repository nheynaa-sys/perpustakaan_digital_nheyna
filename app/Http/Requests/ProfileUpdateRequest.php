<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Aturan validasi untuk update profil.
     */
    public function rules(): array
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
        ];

        if ($this->user()->isAdmin()) {
            $rules['email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ];
        }

        return $rules;
    }
}
