<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'questionnaireId','sellerId','description','amounts','cartId'
   ];
}
