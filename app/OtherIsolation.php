<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherIsolation extends Model
{
    protected $guarded = [];
    protected $table ='other_isolation';
    protected $primaryKey = 'id';
}
