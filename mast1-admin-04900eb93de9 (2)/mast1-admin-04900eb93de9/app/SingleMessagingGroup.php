<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SingleMessagingGroup extends Model
{
    protected $fillable = [
        'groupId','createId','sameId','adminId','type',
    ];
}
