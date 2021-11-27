<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    protected $fillable = [
        'questionnaireId','customerId','sellerId','adminId','type',
   ];
}
