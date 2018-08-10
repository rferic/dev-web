<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use App\App;
use App\AppImage;

class AppImageTest extends TestCase
{
   use DatabaseMigrations;

    protected $image;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        factory(App::class)->create()->each(function ($app) {
			$this->image = factory(AppImage::class)->create([
	            'app_id' => $app->id
	        ]);
        });
    }
    
    public function testLocaleBelongsToApp ()
    {
        $this->assertInstanceOf(App::class, $this->image->app);
    }
}
