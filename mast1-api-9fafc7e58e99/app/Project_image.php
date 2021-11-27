<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project_image extends Model
{
    protected $fillable = [
        'image','sellerId','event','numberAttendees','locationEvent',
   ];
}
