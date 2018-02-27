<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\User;
use App\Page;
use App\PageLocale;

class PageTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $page, $locales;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->page = factory(Page::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->locales = [
            factory(PageLocale::class, 1)->create([
                'lang' => 'en',
                'page_id' => $this->page->id 
            ]),
            factory(PageLocale::class, 1)->create([
                'lang' => 'es',
                'page_id' => $this->page->id 
            ])
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

    public function isAuthor ()
    {
        return $this->owner->id === auth()->id();
    }
}
