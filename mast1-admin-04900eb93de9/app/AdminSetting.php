<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'tax','eventDay','fee','eventDay','email','phoneNo','address','refund'
    ];
}
