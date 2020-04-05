<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    protected $table = 'billdetail';
    public $timestamps = false;
    public $primaryKey  = 'idbillde';
}
