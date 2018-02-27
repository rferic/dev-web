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

class PageTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $page, $locales, $menu;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->page = factory(Page::class)->create([ 'user_id' => $this->user->id ]);
        $this->menu = factory(Menu::class, 1)->create([ 'user_id' => $this->user->id ]);
        $this->locales = [
            
            factory(PageLocale::class, 1)->create([
                'lang' => 'en',
                'page_id' => $this->page->id 
            ])->each(function ($locale) {
                factory(Content::class, 2)->create([
                    'page_locale_id' => $locale->id
                ]);
                
                factory(MenuItem::class, 1)->create([
                    'type' => 'internal',
                    'page_locale_id' => $locale->id,
                    'lang' => $locale->lang
                ]);
            }),
                    
            factory(PageLocale::class, 1)->create([
                'lang' => 'es',
                'page_id' => $this->page->id 
            ])->each(function ($locale) {
                factory(Content::class, 2)->create([
                    'page_locale_id' => $locale->id
                ]);
                
                factory(MenuItem::class, 1)->create([
                    'type' => 'internal',
                    'page_locale_id' => $locale->id,
                    'lang' => $locale->lang
                ]);
            })
            
        ];
    }
    
    public function testPageHasLocales ()
    {
        $this->assertCount(2, $this->page->locales);
        $this->assertInstanceOf(Collection::class, $this->page->locales);
        $this->assertInstanceOf(PageLocale::class, $this->page->locales->first());
    }
    
    public function testPageBelongsToAuthor ()
    {
        $this->assertInstanceOf(User::class, $this->page->author);
    }
    
    public function testPageIsAuthor ()
    {
        $this->assertFalse($this->page->isAuthor());
        $this->signIn($this->user)->assertTrue($this->page->isAuthor());
    }
    
    public function testPageHasThoughtContent ()
    {
        $this->assertCount(4, $this->page->contents);
        $this->assertInstanceOf(Collection::class, $this->page->contents);
        $this->assertInstanceOf(Content::class, $this->page->contents->first());
    }
    
    public function testPageHasThoughtMenuItems ()
    {
        $this->assertCount(2, $this->page->menuItems);
        $this->assertInstanceOf(Collection::class, $this->page->menuItems);
        $this->assertInstanceOf(MenuItem::class, $this->page->menuItems->first());
    }
}
