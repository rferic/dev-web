<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\User;
use App\AppImage;

class App extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [ 'status', 'title', 'description', 'version', 'vue_component', 'type', 'status' ];

    protected static function boot ()
    {
        parent::boot();

        static::deleting (function ($user) {
            if ($user->forceDeleting && !App::runningInConsole()) {
                $user->users()->detach();
            }
        });
    }

    public function images ()
    {
        return $this->hasMany(AppImage::class)->orderBy('priority', 'asc');
    }

    public function users ()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function getCreatedAtAttribute($date){
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }
}
