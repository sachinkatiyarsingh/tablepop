<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = [
        'firstName', 'email', 'password','mobileNo','lastName','status','type','profileImage',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];
}
