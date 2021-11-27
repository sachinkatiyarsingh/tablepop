<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventVendor extends Model
{
    protected $fillable = [
        'vendorId','plannerId','questionnaireId','status',
   ];
}
