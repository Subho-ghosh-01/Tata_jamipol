<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $guarded = [];
    protected $table ='work_order';
    protected $primaryKey = 'id';
}
