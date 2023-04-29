<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest
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
                Rule::unique(User::class)->ignore(\Auth::user()->id)
            ],
            'old_password' => [
                Rule::requiredIf($this->password !== null)
            ],
            'password' => [
                Rule::requiredIf($this->old_password !== null),
                'confirmed'
            ],
            'photos' => 'sometimes|array',
            'photos.*.location' => 'sometimes',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator($validator): void
    {
        if ($this->old_password) {
            $validator->after(function ($validator) {
                if (!\Hash::check($this->old_password, \Auth()->user()->password)) {
                    $validator->errors()->add('old_password', __('message.old_password_incorrect'));
                }
            });
        }
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
        if ($this->has('password')) {
            $validated = parent::validated();
            unset($validated['old_password']);

            return array_merge($validated, ['password' => $this->password]);
        }

        return parent::validated();
    }
}
