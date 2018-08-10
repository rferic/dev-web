<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use App\App;
use App\AppLocale;

class AppLocaleTest extends TestCase
{
    use DatabaseMigrations;

    protected $locale;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        factory(App::class)->create()->each(function ($app) {
			$this->locale = factory(AppLocale::class)->create([
	            'lang' => 'en',
	            'app_id' => $app->id
	        ]);
        });
    }
    
    public function testLocaleBelongsToApp ()
    {
        $this->assertInstanceOf(App::class, $this->locale->app);
    }
}
