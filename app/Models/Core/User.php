<?php

namespace App\Models\Core;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Core\App;
use App\Models\Core\Comment;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot ()
    {
        parent::boot();

        static::deleting (function ($user) {
            if ($user->forceDeleting && !App::runningInConsole()) {
                $user->comments()->forceDelete();
                $user->apps()->detach();
            }
        });
    }

    public function isMe ()
    {
        return $this->id === auth()->id();
    }

    public function isBanned ()
    {
        return $this->trashed();
    }

    public function apps ()
    {
        return $this->belongsToMany(App::class)->withTimestamps();
    }

    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }
}
