<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\Page;
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

    public function node ()
    {
        return $this->belongsTo(Page::class, 'page_id');
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
