<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\Core\App as AppModel;

class AppLocale extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [ 'app_id', 'lang', 'slug', 'title', 'description' ];

    protected static function boot ()
    {
        static::deleted (function ($app_locale) {
            if (!App::runningInConsole()) {
                $app = $app_locale->page();

                if (AppModel::where('app_id', $app_locale->app_id)->get()->count() < 1) {
                    $app->forceDelete();
                }
            }
        });
    }

    public function app ()
    {
        return $this->belongsTo(AppModel::class, 'app_id');
    }
}
