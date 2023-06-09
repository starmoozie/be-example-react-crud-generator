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
        'refund',
        'refund_payabled'
    ];
    protected $casts = [
        'items' => 'array',
    ];
    protected $appends = [
        'totalAmount',
        'alreadyPaid',
    ];

    protected $with = ['customer'];

    public static function getRelationship()
    {
        return (new Self)->with;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function scopeFilterBoolean($query, $filter)
    {
        $column = $filter->id;
        $value = filter_var($filter->value, FILTER_VALIDATE_BOOLEAN);

        return $query->when(
            $value,
            fn ($q) => $q->where(fn ($q) => $q->where($column, '>', 0)->where('refund_payabled', false)),
            fn ($q) => $q->where(fn ($q) => $q->where($column, '<=', 0)->orWhere('refund_payabled', true))
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

    public function getNettoAttribute($value)
    {
        return (int) $value;
    }

    public function getBruttoAttribute($value)
    {
        return (int) $value;
    }
}
