<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $table = 'customer';

    protected $fillable = ['name', 'email', 'mobile',
        'address', 'city', 'pincode',
        'device', 'uuid', 'imei', 'pin'];

    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\wallet', 'user_id');
    }
}
