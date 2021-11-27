<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = "customer_address";
    protected $fillable = [
        'customerId','country','phoneNumber','street','status',
   ];
}
