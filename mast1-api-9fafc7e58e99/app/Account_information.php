<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account_information extends Model
{
    protected $fillable = [
        'sellerId','bankName','sortCode','accountNo','addressOne','addressTwo','addressThree'
   ];

   protected $hidden = [
    'updated_at','created_at'
  ]; 
}
