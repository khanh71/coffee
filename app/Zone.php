<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zone';
    public $timestamps = false;
    public $primaryKey  = 'idzone';
}
