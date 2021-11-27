<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShareEvent extends Model
{
    protected $fillable = [
        'customerId','eventId','email',
    ];
}
