<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';
    public $timestamps = false;
    public $primaryKey  = 'idshop';
}
