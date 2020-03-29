<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $table = 'import';
    public $timestamps = false;
    public $primaryKey  = 'idimp';
}
