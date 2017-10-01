<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use App\Category;
use App\PageLocale;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'category_id', 'user_id' ];

    public function locales ()
    {
        return $this->hasMany(PageLocale::class);
    }

    public function category ()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
