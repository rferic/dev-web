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
    
    protected $page, $locale1, $locale2, $contents, $urlOriginFake = '/fake';
    
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
    
    public function testSeeViewIndex ()
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
    
    public function testSeeViewTrashItem ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.page.trash', $this->page->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Page has been moved to trash')])
                ->assertSessionHas('currentPanel', 'trash');
    }
    
    public function testSeeViewRestoreItem ()
    {
        $this->withExceptionHandling();
                
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.page.restore', $this->page->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Page has been restored')])
                ->assertSessionMissing('currentPanel');
    }
    
    public function testSeeViewDestroyItem ()
    {
        $this->withExceptionHandling();
        
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.page.destroy', $this->page->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Page has been removed')])
                ->assertSessionHas('currentPanel', 'trash');
    }
    
    public function testSeePostStoreItem ()
    {
        $this->withExceptionHandling();
        $locale = 'en';
        
        /******* ERRORS *******/
        
        $response1 = $this
                ->actingAs($this->user)
                ->post(route('admin.page.store'), []);
        
        $response1
                ->assertStatus(500);
        
        /******* SUCCESS *******/
        
        $response2 = $this
                ->actingAs($this->user)
                ->post(route('admin.page.store'), ['pageLocale' => $this->locale1, 'locale' => $locale]);
        
        $lastPage = Page::orderBy('id', 'desc')->first();
        
        $response2
                ->assertSuccessful()
                ->assertStatus(200)
                ->assertExactJson([route('admin.page.detail', $lastPage->id)]);
    }
}
