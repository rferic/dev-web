<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\PageLocale;

class Content extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'page_id', 'key', 'text'];

    public function page ()
    {
        return $this->belongsTo(PageLocale::class, 'page_id');
    }
}
