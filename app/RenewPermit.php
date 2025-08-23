<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenewPermit extends Model
{
    protected $guarded = [];
    protected $table ='renew_permit';
    protected $primaryKey = 'id';
}
