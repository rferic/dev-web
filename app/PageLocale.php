<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\PageLocale;
use App\Content;

class PageLocale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'page_id',
        'lang',
        'slug',
        'title',
        'description',
        'layout',
        'options',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    protected static function boot ()
    {
        parent::boot();

        static::deleting (function ($page_locale) {
            $page_locale->contents()->forceDelete();
        });
    }

    public function page ()
    {
        return $this->belongsTo(PageLocale::class, 'page_locale_id');
    }

    public function contents ()
    {
        return $this->hasMany(Content::class);
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
