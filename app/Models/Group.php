<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
