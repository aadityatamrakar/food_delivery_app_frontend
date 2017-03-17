<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wallet extends Model
{
    protected $table = 'wallet';

    protected $fillable = ['type', 'capture', 'mode', 'amount', 'order_id', 'user_id', 'restaurant_id', 'device', 'uuid', 'imei', 'reason'];

}
