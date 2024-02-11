<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Laravel\Passport\HasApiTokens;

class User extends Model implements Authenticatable
{
    use HasApiTokens, AuthenticatableTrait;
    protected $fillable = [
        'login', 'password',
    ];
}
