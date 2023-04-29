<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'email' => [
                'required',
                'max:50',
                'email',
                Rule::unique(User::class)->when($this->method() === "PUT", fn ($q) => $q->ignore($this->user))
            ],
            'password' => [
                Rule::requiredIf($this->method() === "POST"),
                'confirmed'
            ],
        ];
    }

    /**
     * Modify request after validation passed
     */
    public function passedValidation(): void
    {
        if ($this->password) {
            $this->merge(['password' => \Hash::make($this->password)]);
        }
    }

    /**
     * Modify request validated rules
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        if ($this->password) {
            return array_merge($validated, ['password' => $this->password]);
        }
        unset($validated['password']);

        return $validated;
    }
}
