<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;

use App\User;
use App\Page;
use App\PageLocale;
use App\Content;

class PageAdminTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $page, $locale1, $locale2, $contents;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        
        factory(Page::class, 1)->create()->each(function ($page) {
            $this->page = $page;
            
            $this->locale1 = factory(PageLocale::class)->create([
                'lang' => 'en',
                'page_id' => $page->id
            ]);
            
            $this->locale2 = factory(PageLocale::class)->create([
                'lang' => 'es',
                'page_id' => $page->id
            ]);
            
            $this->contents = factory(Content::class, 3)->create([
                'page_locale_id' => $this->locale1->id
            ]);
        });
    }
    
    public function testSeeViewList ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.pages'));
        
        $response
                ->assertStatus(200)
                ->assertSee(strval($this->page->id))
                ->assertSee($this->locale1->slug)
                ->assertSee($this->locale2->slug);
    }
    
    public function testSeeViewCreate ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.page.create'));
        
        $response
                ->assertStatus(200);
    }
    
    public function testSeeViewDetail ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.page.detail', $this->page->id));
        
        $response
                ->assertStatus(200)
                ->assertSee($this->locale1->title)
                ->assertSee($this->locale1->slug)
                ->assertSee($this->locale1->description)
                ->assertSee($this->locale1->seo_title)
                ->assertSee($this->locale1->seo_description);
        
        foreach ($this->contents AS $content) {
            $response
                    ->assertSee($content->key)
                    ->assertSee($content->id_html)
                    ->assertSee($content->class_html);
        }
    }
}
