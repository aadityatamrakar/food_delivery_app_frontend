<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    protected $fillable = [
        'name'
    ];

    public function restaurants()
    {
        return $this->hasMany('App\Restaurant');
    }
}
