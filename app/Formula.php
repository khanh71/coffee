<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    protected $table = 'formula';
    public $timestamps = false;
    public $primaryKey  = 'idfo';
}
