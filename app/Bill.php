<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bill';
    public $timestamps = false;
    public $primaryKey  = 'idbill';
}
