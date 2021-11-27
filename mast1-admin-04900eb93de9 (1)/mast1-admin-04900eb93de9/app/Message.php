<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sentby','sentto','transactionId','message','type','readStatus',
   ];

    protected $hidden = [
        'created_at'
    ]; 
}
