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
        switch ($this->method()) {
            case 'PUT':
                return [
                    'name' => 'required|max:50',
                    'phone' => 'nullable|sometimes|numeric',
                ];
                break;

            default:
                return [
                    'items' => [
                        'required', 'array'
                    ],
                    'items.*.name' => [
                        'required', 'max:50'
                    ],
                    'items.*.phone' => [
                        'nullable', 'sometimes',
                        'numeric'
                    ],
                ];
        }
    }

    /**
     * Merge request after validation
     */
    public function passedValidation(): void
    {
        if ($this->method() === "POST") {
            foreach ($this->items as $item) {
                $newFormData[] = [
                    'name'  => $item['name'],
                    'phone' => $item['phone'],
                ];
            }

            $this->merge(['items' => $newFormData]);
        }
    }
}
