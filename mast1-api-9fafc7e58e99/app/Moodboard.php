<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moodboard extends Model
{
    protected $fillable = [
        'name','sellerId','description','previewImage',
   ];
}
