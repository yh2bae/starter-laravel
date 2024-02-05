<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return asset('storage/avatars/' . $value);
        }
        return asset('assets/img/avatars/1.png');
    }
}
