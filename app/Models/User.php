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
    const MSG_FROM_PUBLIC = 1;
    const MSG_FROM_ANONYMOUS = 2;
    const MSG_FROM_REGISTERED_USER = 3;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_picture',
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

    public function confessions () {
        return $this->hasMany(Confession::class, 'receiver_id');
    }

    public function profilePicture () {
        if (isset($this->profile_picture)) {
            return url($this->profile_picture);
        }

        return 'https://loremflickr.com/320/240';
    }

    public function isBanned () {
        return (bool) $this->is_banned;
    }

    public function isReceivingMessage () {
        return !$this->isBanned() && $this->message_privacy > self::MSG_FROM_NOBODY;
    }

    public function canReceiveWith ($privacy) {
        return $this->isReceivingMessage() && $privacy >= $this->message_privacy ? true : false;
    }

    public function whyCannotReceiveTranslation () {
        return $this->messageFromTranslation($this->message_privacy);
    }

    public function messageFromTranslation ($from) {
        switch ( $from ) {
            case self::MSG_FROM_NOBODY:
                return $this->username . ' is not receiving message from anyone.';
            case self::MSG_FROM_ANONYMOUS:
                return $this->username . ' is not receiving message from anonymous user.';
            case self::MSG_FROM_REGISTERED_USER:
                return $this->username . ' is not receiving message except real user.';
            case self::MSG_FROM_PUBLIC:
                return $this->username . ' is not receiving message from public';
        }
    }
}
