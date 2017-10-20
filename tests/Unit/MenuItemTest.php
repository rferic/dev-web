<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use Spatie\Permission\Models\Role;

use App\User;
use App\Menu;
use App\MenuItem;
use App\Page;
use App\PageLocale;

class MenuItemTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $menu, $menuItemInternal, $menuItemExternal, $page, $pageLocale;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->page = factory(Page::class)->create();
        $this->pageLocale = factory(PageLocale::class)->create([
            'lang' => 'en',
            'page_id' => $this->page->id
        ]);
        $this->menu = factory(Menu::class)->create();
        $this->menuItemInternal = factory(MenuItem::class)->create([
            'type' => 'internal',
            'page_locale_id' => $this->pageLocale->id,
            'lang' => $this->pageLocale->lang
        ]);
        $this->menuItemExternal = factory(MenuItem::class)->create([
            'type' => 'external',
            'page_locale_id' => null,
            'lang' => $this->pageLocale->lang,
            'url_external' => 'www.google.es'
        ]);
    }
    
    public function testMenuItemBelongsToAuthor ()
    {
        $this->assertInstanceOf(User::class, $this->menuItemInternal->author);
    }
    
    public function testMenuItemInternalBelongsToPageLocale ()
    {
        $this->assertInstanceOf(PageLocale::class, $this->menuItemInternal->pageLocale);
    }
    
    public function testMenuItemInternalBelongsToPage ()
    {
        $this->assertInstanceOf(Page::class, $this->menuItemInternal->pageLocale->page);
    }
}
