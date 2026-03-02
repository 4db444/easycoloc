<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'is_admin',
        'is_banned',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_banned' => 'boolean',
    ];

    public function groups () : BelongsToMany {
        return $this->belongsToMany(
            Group::class,
            "group_members"
        )->withTimestamps();
    }

    public function active_groups () : BelongsToMany {
        return $this->belongsToMany(
            Group::class,
            "group_members"
        )->wherePivotNull("left_at");
    }

    public function expenses () : HasMany {
        return $this->hasMany(Expense::class);
    }

    public function settlementsFrom () : HasMany {
        return $this->hasMany(Settlement::class, 'from_user_id');
    }

    public function settlementsTo () : HasMany {
        return $this->hasMany(Settlement::class, 'to_user_id');
    }
}
