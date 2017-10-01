<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Page;
use App\User;

class CategoryLocale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',
        'lang',
        'slug',
        'title',
        'description',
        'layout',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    public function getRouteKeyName ()
    {
        return 'slug';
    }

    public function node ()
    {
        return $this->belongsTo(Page::class, 'page_id');
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
