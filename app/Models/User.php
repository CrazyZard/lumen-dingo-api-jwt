<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'email','name','password'
    ];
    protected $hidden = [
        'password','created_at','updated_at'
    ];
    public function getJWTIdentifier()
    {

        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}