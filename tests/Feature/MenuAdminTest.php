<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;

use App\User;
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
        $this->menu = factory(Menu::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->items = factory(MenuItem::class, 5)->make([
            'menu_id' => $this->menu->id,
            'user_id' => $this->user->id
        ]);
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
        
        $items = factory(MenuItem::class, 5)->make([
            'menu_id' => $this->menu->id,
            'user_id' => $this->user->id,
            'type' => 'external',
            'page_locale_id' => null
        ]);
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
        $items = factory(MenuItem::class, 5)->create([
            'menu_id' => $this->menu->id,
            'user_id' => $this->user->id,
            'type' => 'external',
            'lang' => $locale,
            'page_locale_id' => null
        ]);
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
        
        factory(MenuItem::class, 5)->create([
            'menu_id' => $this->menu->id,
            'user_id' => $this->user->id,
            'type' => 'external',
            'lang' => $locale,
            'page_locale_id' => null
        ]);
        
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
