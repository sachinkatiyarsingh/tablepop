<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customerId', 'questionnaireId', 'planId','transactionId','amount','status','sellerId',
    ];


       protected $hidden = [
    'updated_at','json'
];
}
