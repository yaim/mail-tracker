<?php

namespace App;

use App\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute()
    {
        $emailHash = md5(strtolower($this->attributes['email']));

        return 'https://s.gravatar.com/avatar/'.$emailHash.'?s=80';
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
