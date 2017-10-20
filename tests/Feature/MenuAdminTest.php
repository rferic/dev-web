<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;

use App\User;
use App\Page;
use App\PageLocale;
use App\Menu;
use App\MenuItem;

class MenuAdminTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user, $menu, $items, $urlOriginFake = '/fake';
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        
        factory(Page::class, 8)->create()->each(function ($page) {
            $random = (bool)random_int(0, 1);
            
            if ($random) {
                factory(PageLocale::class)->create([
                    'lang' => 'en',
                    'page_id' => $page->id
                ]);
            }
            
            if (!$random || (bool)random_int(0, 1)) {
                factory(PageLocale::class)->create([
                    'lang' => 'es',
                    'page_id' => $page->id
                ]);
            }
        });
        
        $this->menu = factory(Menu::class)->create();
        $this->items = factory(MenuItem::class, 5)->make();
    }
    
    public function testSeeViewIndex ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.menu.index'));
        
        $response
                ->assertStatus(200)
                ->assertSee($this->menu->name)
                ->assertSee($this->menu->description);
    }
    
    public function testSeeViewDetail ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.menu.detail', $this->menu->id));
        
        $response
                ->assertStatus(200)
                ->assertSee($this->menu->name)
                ->assertSee($this->menu->description);
    }
    
    public function testAddMenu ()
    {
        $this->withExceptionHandling();
        
        $menu = factory(Menu::class)->make();
        
        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.menu.store'), []);
        
        $response
                ->assertStatus(302)
                ->assertSessionHasErrors('name')
                ->assertSessionHasErrors('description');
        
        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.menu.store'), $menu->toArray());
        
        $response
                ->assertStatus(302)
                ->assertSessionHas('message', __('Menu has been created'));
    }
    
    public function testEditMenu ()
    {
        $this->withExceptionHandling();
        
        $menu = factory(Menu::class)->make();
        
        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->put(route('admin.menu.edit', $this->menu->id));
        
        $response
                ->assertStatus(302)
                ->assertSessionHasErrors('name')
                ->assertSessionHasErrors('description');
        
        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->put(route('admin.menu.edit', $this->menu->id), $menu->toArray());
        
        $response
                ->assertStatus(302)
                ->assertRedirect(route('admin.menu.detail', $this->menu->id))
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Menu has been updated')]);
        
    }
    
    public function testSaveMenu ()
    {
        $this->withExceptionHandling();
        
        $items = factory(MenuItem::class, 5)->make();
        $locale = 'en';
        
        /******* TESING PUSH NEW ITEMS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.menu.save', $this->menu->id), [
                    'locale' => $locale,
                    'items' => $items,
                    'itemsForRemove' => []
                ]);
        
        $response
                ->assertSuccessful()
                ->assertExactJson([true]);
        
        /******* TESING PUSH UPDATE & REMOVE ITEMS *******/
        $items = factory(MenuItem::class, 5)->create();
        $items[0]->edit = true;
        $items[0]->priority = 8;
        
        $itemsForUpdate = [
            $items[0],
            $items[2],
            $items[3],
            $items[4],
        ];
        $itemsForRemove = [$items[1]];
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.menu.save', $this->menu->id), [
                    'locale' => $locale,
                    'items' => $itemsForUpdate,
                    'itemsForRemove' => $itemsForRemove
                ]);
        
        $response
                ->assertSuccessful()
                ->assertExactJson([true]);
    }
    
    public function testTrashMenu ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.menu.trash', $this->menu->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Menu has been moved to trash')])
                ->assertSessionHas('currentPanel', 'trash');
        
    }
    
    public function testRestoreMenu ()
    {
        $this->withExceptionHandling();
        
        $this->menu->delete();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.menu.restore', $this->menu->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Menu has been restored')])
                ->assertSessionMissing('currentPanel');
        
    }
    
    public function testDestroyMenu ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.menu.destroy', $this->menu->id), ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertStatus(302)
                ->assertRedirect($this->urlOriginFake)
                ->assertSessionHas('message', ['class' => 'alert-success', 'content' => __('Menu has been removed')])
                ->assertSessionHas('currentPanel', 'trash');
    }
    
    public function testGetItems ()
    {
        $this->withExceptionHandling();
        
        $locale = 'en';
        
        factory(MenuItem::class, 5)->create();
        
        $expectedResponse = $this->menu
                ->items()
                ->where('lang', $locale)
                ->with('pageLocale')
                ->orderBy('priority', 'ASC')
                ->get(['id', 'label', 'type', 'page_locale_id', 'url_external', 'priority'])
                ->toArray();
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.menu.getItemsLocale', $this->menu->id), [ 'locale' => $locale ], ['HTTP_REFERER' => $this->urlOriginFake]);
        
        $response
                ->assertSuccessful()
                ->assertJson($expectedResponse);
    }
}
