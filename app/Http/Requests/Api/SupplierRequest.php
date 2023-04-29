<?php

namespace App\Http\Requests\Api;

class SupplierRequest extends BaseRequest
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
            'phone' => 'nullable|sometimes|numeric',
        ];
    }
}
