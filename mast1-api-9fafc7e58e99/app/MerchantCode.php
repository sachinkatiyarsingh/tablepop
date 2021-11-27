<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantCode extends Model
{
    protected $fillable = [
        'code','category',
   ];

    protected $hidden = [
    'updated_at','created_at'
    ]; 
}
