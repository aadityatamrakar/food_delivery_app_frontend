<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stations extends Model
{
    protected $table = 'stations';

    protected $fillable = ['train_no', 'data'];
}
