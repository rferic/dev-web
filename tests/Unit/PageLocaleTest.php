<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\User;
use App\Page;
use App\PageLocale;

class PageLocaleTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $page, $locale;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->page = factory(Page::class)->create([ 'user_id' => $this->user->id ]);
        $this->locale = factory(PageLocale::class)->create([
            'lang' => 'en',
            'page_id' => $this->page->id 
        ]);
    }
}
