<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required|max:50',
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique(User::class)
            ],
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * pass property after validastion passed.
     */
    public function passedValidation(): void
    {
        $this->merge(['password' => \Hash::make($this->password)]);
    }
}
