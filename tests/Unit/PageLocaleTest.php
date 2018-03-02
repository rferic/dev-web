<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\User;
use App\Page;
use App\PageLocale;
use App\Content;
use App\Menu;
use App\MenuItem;

class PageLocaleTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $page, $locale, $menu;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->page = factory(Page::class)->create([ 'user_id' => $this->user->id ]);
        $this->menu = factory(Menu::class, 1)->create([ 'user_id' => $this->user->id ]);
        $this->locale = factory(PageLocale::class)->create([
            'lang' => 'en',
            'page_id' => $this->page->id 
        ]);
        
        factory(Content::class, 2)->create([ 'page_locale_id' => $this->locale->id ]);
        
        factory(MenuItem::class, 3)->create([
            'type' => 'internal',
            'page_locale_id' => $this->locale->id,
            'lang' => $this->locale->lang
        ]);
    }
    
    public function testLocaleBelongsToPage ()
    {
        $this->assertInstanceOf(Page::class, $this->locale->page);
    }
    
    public function testLocaleHasContents ()
    {
        $this->assertCount(2, $this->locale->contents);
        $this->assertInstanceOf(Collection::class, $this->locale->contents);
        $this->assertInstanceOf(Content::class, $this->locale->contents->first());
    }
    
    public function testLocaleBelongsToAuthor ()
    {
        $this->assertInstanceOf(User::class, $this->locale->author);
    }
    
    public function testLocaleIsAuthor ()
    {
        $this->assertFalse($this->locale->isAuthor());
        $this->signIn($this->user)->assertTrue($this->locale->isAuthor());
    }
    
    public function testLocaleHasMenuItems ()
    {
        $this->assertCount(3, $this->locale->menuItems);
        $this->assertInstanceOf(Collection::class, $this->locale->menuItems);
        $this->assertInstanceOf(MenuItem::class, $this->locale->menuItems->first());
    }
}
