<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    protected $table = 'otp';

    protected $fillable = ['mobile' ,'otp', 'res'];
}
