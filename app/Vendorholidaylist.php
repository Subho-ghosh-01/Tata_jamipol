<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendorholidaylist extends Model
{
    protected $guarded = [];
    protected $table = 'vendor_holiday_list';
    protected $primaryKey = 'id';

    public $timestamps = false; // ✅ This disables created_at and updated_at

    protected $fillable = [
        'pno',
        'year',
        'name',
        'pl',
        'fl',
        'cl',
        'flp',
        'spl',
        'created_by',
        'created_datetime',
        'updated_by',
        'updated_datetime'
    ];
}
