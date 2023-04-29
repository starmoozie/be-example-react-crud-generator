<?php

namespace App\Models;

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
