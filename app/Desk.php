<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desk extends Model
{
    protected $table = 'desk';
    public $timestamps = false;
    public $primaryKey  = 'iddesk';
}
