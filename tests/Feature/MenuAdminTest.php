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
    
    protected $user, $menu, $items;
    
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
}
