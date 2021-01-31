<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Confession extends Model
{
    use SoftDeletes;

    protected $table = 'confessions';
    protected $fillable = [
        'receiver_id',
        'body',
        'poster_id',
        'is_anonymous',
    ];

    public function poster () {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function receiver () {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function user () {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getPostedAtAttribute () {
        return $this->created_at->toDateTimeString();
    }

    public function getIsPublicAttribute () {
        return $this->poster_id ? false : true;
    }
}
