<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupon';

    protected $fillable = ['code', 'max_amount', 'percent', 'min_amt', 'return_type', 'valid_from', 'valid_till', 'times', 'new_only'];

}
