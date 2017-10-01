<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\Page;
use App\CategoryLocale;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id', 'type' ];

    public function author ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAuthor ()
    {
        return $this->owner->id === auth()->id();
    }

    public function locales ()
    {
        return $this->hasMany(CategoryLocale::class);
    }

    public function pages ()
    {
        return $this->hasMany(Page::class);
    }
}
