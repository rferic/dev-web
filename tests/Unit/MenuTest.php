<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\User;
use App\Menu;
use App\MenuItem;
use App\Page;
use App\PageLocale;

class MenuTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $menu, $menuItems, $pages;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->pages = factory(Page::class, 3)->create()->each(function ($page) {
            factory(PageLocale::class)->create([
                'lang' => 'en',
                'page_id' => $page->id
            ]);
            
            factory(PageLocale::class)->create([
                'lang' => 'es',
                'page_id' => $page->id
            ]);
        });
        $this->menu = factory(Menu::class)->create([
            'user_id' => $this->user->id
        ]);
    }
    
    public function testMenuBelongsToAuthor ()
    {
        $this->assertInstanceOf(User::class, $this->menu->author);
    }
    
    public function testMenuIsAuthor ()
    {
        $this->assertFalse($this->menu->isAuthor());
        $this->signIn($this->user)->assertTrue($this->menu->isAuthor());
    }
    
    public function testMenuHasMenuItems ()
    {
        factory(MenuItem::class, 5)->create();
        
        $this->assertCount(5, $this->menu->items);
        $this->assertInstanceOf(Collection::class, $this->menu->items);
        $this->assertInstanceOf(MenuItem::class, $this->menu->items->first());
    }
}
