<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\PageLocale;
use App\Content;
use App\MenuItem;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id' ];

    protected static function boot ()
    {
        parent::boot();

        static::deleting (function ($page) {
            if ($page->forceDeleting) {
                $page->contents()->forceDelete();
                $page->menuItems()->forceDelete();
                $page->locales()->forceDelete();
            }
        });
    }

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
    
    public function contents ()
    {
        return $this->hasManyThrough(Content::class, PageLocale::class);
    }
    
    public function menuItems ()
    {
        return $this->hasManyThrough(MenuItem::class, PageLocale::class);
    }
}
