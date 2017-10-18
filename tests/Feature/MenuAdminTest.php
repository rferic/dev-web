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
    
    public function testUpdateMenu ()
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
        
    }
    
    public function testDestroyMenu ()
    {
        $this->withExceptionHandling();
    }
}
