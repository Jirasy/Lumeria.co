<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone', 'customer_address',
        'subtotal', 'payment_method', 'tenor_months', 'margin_rate',
        'margin_total', 'installment_amount', 'grand_total', 'status',
    ];

    protected $casts = [
        'subtotal'           => 'integer',
        'tenor_months'       => 'integer',
        'margin_rate'        => 'float',
        'margin_total'       => 'integer',
        'installment_amount' => 'integer',
        'grand_total'        => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}