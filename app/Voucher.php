<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'voucher';
    public $timestamps = false;
    public $primaryKey  = 'idvoucher';
}
