<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\App;
use App\User;
use App\AppLocale;
use App\AppImage;

class AppTest extends TestCase
{
    use DatabaseMigrations;

    protected $appTest;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        $this->appTest = factory(App::class)->create();
    }

    public function testAppHasUsers ()
    {
    	$count = 10;

        Role::create(['name' => 'public']);
        
        $users = factory(User::class, $count)->create()->each(function ($user) {
            $user->assignRole('public');
        });

        $this->appTest->users()->sync($users);

        $this->assertCount($count, $this->appTest->users);
        $this->assertInstanceOf(Collection::class, $this->appTest->users);
        $this->assertInstanceOf(User::class, $this->appTest->users->first());
    }

    public function testAppHasLocales ()
    {
    	factory(AppLocale::class)->create([
            'lang' => 'en',
            'app_id' => $this->appTest->id
        ]);

        factory(AppLocale::class)->create([
            'lang' => 'es',
            'app_id' => $this->appTest->id
        ]);

        $this->assertCount(2, $this->appTest->locales);
        $this->assertInstanceOf(Collection::class, $this->appTest->locales);
        $this->assertInstanceOf(AppLocale::class, $this->appTest->locales->first());
    }

    public function testAppHasImages ()
    {
    	$count = 3;

        factory(AppImage::class, $count)->create([
            'app_id' => $this->appTest->id
        ]);

        $this->assertCount($count, $this->appTest->images);
        $this->assertInstanceOf(Collection::class, $this->appTest->images);
        $this->assertInstanceOf(AppImage::class, $this->appTest->images->first());
    }
}
