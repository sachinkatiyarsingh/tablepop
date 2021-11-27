<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
   
    protected $fillable = [
        'productId','eventId','sellerId','quantity'
   ];
}
