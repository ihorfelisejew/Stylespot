<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_method',
        'shipping_city',
        'post_office_number',
        'shipping_address',
        'shipping_postal_code',
        'status',
        'total_price',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
