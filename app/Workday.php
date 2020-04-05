<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    protected $table = 'workday';
    public $timestamps = false;
    public $primaryKey  = 'idwd';
}
