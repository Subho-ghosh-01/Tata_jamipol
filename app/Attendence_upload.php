<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendence_upload extends Model
{
    protected $guarded = [];
    protected $table = 'AttendanceLog';
    protected $primaryKey = 'id';
}
