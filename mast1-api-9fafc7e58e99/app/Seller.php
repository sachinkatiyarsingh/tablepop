<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Seller extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'firstName', 'profileName','email', 'password','mobileNo','lastName','status','type','userName','dob','gender','countryId','addressTwo',
    ];

    protected $hidden = [
        'password', 'remember_token','updated_at'
    ];
}
