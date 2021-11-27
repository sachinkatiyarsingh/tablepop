<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'vendorId','plannerId','questionnaireId','readStatus','notification','type',
   ];
}
