<?php

namespace App\Models;

use App\Constants\Inc;

class Sale extends BaseModel
{
    protected $fillable = [
        'date',
        'customer_id',
        'receipt_number',
        'checkout_amount',
        'payment_method_id',
        'pay_amount',
        'refund_amount',
        'items',
        'brutto',
        'netto',
        'unpaid',
        'refund'
    ];
    protected $casts = [
        'items' => 'array',
    ];
    protected $appends = [
        'totalAmount',
        'alreadyPaid',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function scopeFilterAnyColumns($query, $filter, $with = [])
    {
        $is_bool = \in_array($filter->value, Inc::BOOL_STRING);
        $is_string = \is_string($filter->value);
        $is_object = \is_object($filter->value);
        $is_relation = \in_array($filter->id, $with);

        return $query
            ->when(
                // If column is from current table
                !$is_relation && $is_string && !$is_bool,
                fn ($q) => $q->where($filter->id, "LIKE", "%{$filter->value}%")
            )
            ->when(
                // If column is related model N - 1
                $is_relation && $is_string,
                fn ($q) => $q->whereHas($filter->id, fn ($q) => $q->where('name', 'like', "%{$filter->value}%"))
            )
            ->when(
                // Indicate if range {min: "", max: ""}
                $is_object,
                fn ($q) => $q->whereBetween($filter->id, [$filter->value->min, $filter->value->max])
            )
            ->when(
                // Indicate if less or more than, from boolean value
                $is_bool,
                fn ($q) => $q->filterRefundOrUnpaid($filter)
            );
    }

    public function scopeFilterRefundOrUnpaid($query, $filter)
    {
        $column = $filter->id;
        $value = filter_var($filter->value, FILTER_VALIDATE_BOOLEAN);

        return $query->when(
            $value,
            fn ($q) => $q->where($column, '>', 0),
            fn ($q) => $q->where($column, '<=', 0)
        );
    }

    public function getTotalAmountAttribute()
    {
        return collect($this->items)->sum('sell_price');
    }

    public function getAlreadyPaidAttribute()
    {
        return $this->pay_amount + $this->checkout_amount;
    }
}
