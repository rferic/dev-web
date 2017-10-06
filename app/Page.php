<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\PageLocale;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id' ];

    public function locales ()
    {
        return $this->hasMany(PageLocale::class);
    }

    public function author ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAuthor ()
    {
        return $this->owner->id === auth()->id();
    }
}
