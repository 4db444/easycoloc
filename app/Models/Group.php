<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = ["name", "admin_id"];

    public function admin () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function members () :BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "group_members"
        )->withPivot("left_at")
        ->withTimestamps();
    }

    public function activeMembers () :BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "group_members"
        )->wherePivotNull("left_at");
    }

    public function pastMembers () :BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "group_members"
        )->wherePivotNotNull("left_at")
        ->withPivot("left_at");
    }

    public function allMembers () :BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "group_members"
        )->withPivot("left_at");
    }

    public function categories () :HasMany {
        return $this->hasMany(Category::class);
    }

    public function tokens () :HasMany {
        return $this->hasMany(Token::class);
    }

    public function expenses () :HasMany {
        return $this->hasMany(Expense::class);
    }

    public function expenseShares () :HasMany {
        return $this->hasMany(GroupExpenseShare::class);
    }

    public function settlements () :HasMany {
        return $this->hasMany(Settlement::class);
    }
}
