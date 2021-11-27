<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerVendor extends Model
{
    protected $fillable = [
         'customerId','eventId','sellerId','status'
    ];
}
