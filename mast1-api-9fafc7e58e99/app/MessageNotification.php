<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageNotification extends Model
{
  
    protected $table = 'message_notifications';
    protected $fillable = [
        'groupId','userId','type','message',
    ];
}
