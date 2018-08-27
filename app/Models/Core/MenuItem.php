<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Core\User;
use App\Models\Core\Menu;
use App\Models\Core\PageLocale;

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
        return $this->belongsTo(PageLocale::class, 'page_locale_id');
    }

    public function author ()
    {
        return $this->belongsTo(User::class, 'user_id');
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
