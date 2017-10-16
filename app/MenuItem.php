<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\Menu;
use App\PageLocale;

class MenuItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'menu_id',
        'lang',
        'label',
        'type',
        'page_locale_id',
        'url_external',
        'priority'
    ];
    protected $happens = ['page'];

    public function menu ()
    {
        return $this->belongsTo(Menu::class);
    }

    public function pageLocale ()
    {
        return $this->belongsTo(PageLocale::class);
    }

    public function author ()
    {
        return $this->belongsTo(User::class);
    }

    public function isAuthor ()
    {
        return $this->owner->id === auth()->id();
    }
    
    public function getPageAttribute ()
    {
        return $this->pageLocale->page;
    }
}
