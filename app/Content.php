<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\PageLocale;

class Content extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'page_locale_id', 'key', 'text'];

    public function page_locale ()
    {
        return $this->belongsTo(PageLocale::class, 'page_locale_id');
    }
}
