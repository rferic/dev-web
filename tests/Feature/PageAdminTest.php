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
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.page.store'), []);
        
        $response
                ->assertStatus(500);
        
        /******* SUCCESS *******/        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.page.store'), ['pageLocale' => $this->locale1, 'locale' => $locale]);
        
        $lastPage = Page::orderBy('id', 'desc')->first();
        
        $response
                ->assertSuccessful()
                ->assertExactJson([route('admin.page.detail', $lastPage->id)]);
    }
    
    public function testSeePostUpdateItemError ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.page.update', $this->page->id), []);
        
        $response
                ->assertStatus(500);
    }
    
    public function testSeePostCreateItemLocale ()
    {
        $this->withExceptionHandling();
        
        $locale = 'en';
        $this->locale1->forceDelete();
        $params = $this->locale1->toArray();
        $params['id'] = null;
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.page.update', $this->page->id), ['pageLocale' => $params, 'locale' => $locale]);
        
        $response
                ->assertSuccessful()
                ->assertExactJson([true]);
    }
    
    public function testSeePostUpdateItemLocale ()
    {
        $this->withExceptionHandling();
        
        $locale = 'en';
        $params = $this->locale1->toArray();
        $params['id'] = $this->page->id;
        $params['lang'] = $locale;
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.page.update', $this->page->id), ['pageLocale' => $params, 'locale' => $locale]);
        
        $response
                ->assertSuccessful()
                ->assertExactJson([true]);
    }
    
    public function testSeePostDestroyPageLocale ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.pagelocale.destroyPageLocale', $this->page->id), ['locale' => 'en'])
                ->assertSuccessful();
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.pagelocale.destroyPageLocale', $this->page->id), ['locale' => 'es'])
                ->assertSuccessful()
                ->assertExactJson([true]);
    }

    public function testSeePostStoreContent ()
    {
        $this->withExceptionHandling();

        $params = $this->contents[0]->toArray();

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.store', $this->page->id), [])
                ->assertStatus(500);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.store', $this->page->id), ['content' => $params])
                ->assertSuccessful();
    }

    public function testSeePostUpdateContent ()
    {
        $this->withExceptionHandling();

        $params = $this->contents[0]->toArray();
        $paramsOverride = $this->contents[1]->toArray();

        foreach ($params AS $key => $param) {
            if ($key !== 'id' && $key !== 'page_locale_id' && $key !== 'created_at' && $key !== 'updated_at' && $key !== 'deleted_at') {
                $params[$key] = $paramsOverride[$key];                
            }
        }

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.update', $this->page->id), [])
                ->assertStatus(500);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.update', $this->page->id), ['content' => $params])
                ->assertSuccessful()
                ->assertExactJson([true]);
    }

    public function testSeePostDestroyContent ()
    {
        $this->withExceptionHandling();

         $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.destroy', $this->page->id), [])
                ->assertStatus(404);
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.content.destroy', $this->page->id), ['content' => $this->contents[1]->id])
                ->assertSuccessful()
                ->assertExactJson([true]);
    }
}
