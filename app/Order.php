<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['user_id', 'restaurant_id', 'address', 'city', 'gtotal', 'cart', 'status', 'deliver', 'payment_modes', 'coupon', 'delivery_fee', 'packing_fee'];

    public function user()
    {
        return $this->belongsTo('App\Customer');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
