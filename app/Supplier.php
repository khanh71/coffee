<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    public $timestamps = false;
    public $primaryKey  = 'idsupp';
}
