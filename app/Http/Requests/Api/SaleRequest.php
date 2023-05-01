<?php

namespace App\Http\Requests\Api;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class SaleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required'
            ],
            'customer.id' => [
                'required',
                Rule::exists(Customer::class, 'id')
            ],
            'receipt_number' => [
                'required',
                Rule::unique(Sale::class)->when($this->method() === "PUT", fn ($q) => $q->ignore($this->sale))
            ],
            'checkout_amount' => [
                'required'
            ],
            'paymentMethod.id' => [
                'nullable',
                'sometimes',
                Rule::exists(PaymentMethod::class, 'id')
            ],
            'pay_amount' => [
                'nullable',
                'sometimes',
                'numeric'
            ],
            'refund_payabled' => [
                'nullable',
                'sometimes',
                'boolean'
            ],
            'items' => [
                'required', 'array'
            ],
            'items.*.product.id' => [
                'required',
                Rule::exists(Product::class, 'id')
            ],
            'items.*.sell_price' => [
                'nullable',
                'sometimes',
                'numeric'
            ],
        ];
    }

    /**
     * Merge request after validation
     */
    public function passedValidation(): void
    {
        $this->merge([
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'customer_id' => $this->customer['id'],
            'payment_method_id' => $this->paymentMethod['id']
        ]);
    }

    /**
     * Modify request validated rules
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        unset($validated['customer']);
        unset($validated['paymentMethod']);

        $brutto = $this->calculateBrutto();

        $calculate = ($this->checkout_amount + $this->pay_amount) - collect($this->items)->sum('sell_price');

        return array_merge($validated, [
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'customer_id' => $this->customer['id'],
            'payment_method_id' => $this->paymentMethod['id'],
            'items' => $this->items,
            'brutto' => $brutto,
            'netto' => $this->calculateNetto($brutto),
            'unpaid' => $calculate > 0 ? 0 : abs($calculate),
            'refund' => $calculate > 0 ? $calculate : 0
        ]);
    }

    /**
     * Calculates brutto
     */
    protected function calculateBrutto(): float
    {
        $total_sell = 0;
        $total_buy = 0;
        foreach ($this->items as $item) {
            $total_sell += $item['sell_price'];
            $total_buy += $item['product']['buy_price'];
        }

        return $total_sell - $total_buy;
    }

    /**
     * Calculates netto
     */
    protected function calculateNetto($brutto): float
    {
        return $brutto - (config('base.tax') / 100 * $this->checkout_amount);
    }
}
