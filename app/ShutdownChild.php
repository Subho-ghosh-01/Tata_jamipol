<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShutdownChild extends Model
{
    protected $guarded = [];
    protected $table ='shutdownchilds';
    protected $primaryKey = 'id';
}
