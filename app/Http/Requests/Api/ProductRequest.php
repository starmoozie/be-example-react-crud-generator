<?php

namespace App\Http\Requests\Api;

use App\Models\Supplier;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'PUT': // If update
                return [
                    'name' => 'required|max:50',
                    'buy_price' => [
                        'sometimes', 'nullable', 'regex:/^\d{4,15}$/'
                    ],
                    'supplier.id' => [
                        'nullable', 'sometimes',
                        Rule::exists(Supplier::class, 'id')
                    ],
                    'productCategory.id' => [
                        'nullable', 'sometimes',
                        Rule::exists(ProductCategory::class, 'id')
                    ],
                ];

            default:
                return [
                    'supplier.id' => [
                        'required', 'sometimes',
                        Rule::exists(Supplier::class, 'id')
                    ],
                    'items' => [
                        'required', 'array'
                    ],
                    'items.*.name' => [
                        'required'
                    ],
                    'items.*.productCategory.id' => [
                        'nullable', 'sometimes',
                        Rule::exists(ProductCategory::class, 'id')
                    ],
                    'items.*.buy_price' => [
                        'nullable', 'sometimes', 'regex:/^\d{4,15}$/',
                    ]
                ];
        }
    }

    /**
     * Merge request after validation
     */
    public function passedValidation(): void
    {
        // If not update
        if ($this->method() === "PUT") {
            $this->merge([
                'supplier_id' => $this->supplier['id'],
                'buy_price'   => $this->buy_price,
                'name'        => $this->name,
                'product_category_id' => $this->productCategory['id']
            ]);
        } else {
            foreach ($this->items as $item) {
                $newFormData[] = [
                    'supplier_id' => $this->supplier['id'],
                    'buy_price'   => $item['buy_price'],
                    'name'        => $item['name'],
                    'product_category_id' => $item['productCategory']['id'],
                ];
            }

            $this->merge(['items' => $newFormData]);
        }
    }

    /**
     * Modify request validated rules
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        unset($validated['productCategory']);
        unset($validated['supplier']);
        return array_merge($validated, [
            'supplier_id' => $this->supplier['id'],
            'buy_price'   => $this->buy_price,
            'name'        => $this->name,
            'product_category_id' => $this->productCategory['id']
        ]);
    }
}
