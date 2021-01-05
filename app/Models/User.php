<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;
    const MSG_FROM_NOBODY = 0;
    const MSG_FROM_REGISTERED_USER = 1;
    const MSG_FROM_ANONYMOUS = 2;
    const MSG_FROM_PUBLIC = 3;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute ($value) {
        $this->attributes['password'] = app('hash')->make($value);
    }

    public function getJWTIdentifier () {
        return $this->getKey();
    }

    public function getJWTCustomClaims () {
        return [];
    }

    public function canReceiveFrom ($from) {
        return $this->isReceivingMessage() && $this->message_from >= $from ? true : false;
    }

    public function isReceivingMessage () {
        return $this->message_from > self::MSG_FROM_NOBODY;
    }

    public function receiveFromTranslation () {
        return $this->messageFromTranslation($this->message_from);
    }

    public function messageFromTranslation ($from) {
        switch ( $from ) {
            case self::MSG_FROM_NOBODY:
                return 'is not receiving message from anyone.';
            case self::MSG_FROM_ANONYMOUS:
                return 'is not receiving message from anonymous user.';
            case self::MSG_FROM_REGISTERED_USER:
                return 'is not receiving message except registered user.';
            case self::MSG_FROM_PUBLIC:
                return 'is not receiving message from public';
        }
    }
}
