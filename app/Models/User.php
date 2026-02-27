<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        "is_admin"
    ];

    protected $hidden = [
        'password',
    ];

    public function groups () : BelongsToMany {
        return $this->belongsToMany(
            Group::class,
            "group_members"
        )->withTimestamps();
    }
}
