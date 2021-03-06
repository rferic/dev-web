<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;

use App\Models\Core\User;

class AdminPanelTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $admin, $user;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'public']);
        
        $this->admin = factory(User::class)->create()->assignRole('admin');
        $this->user = factory(User::class)->create()->assignRole('public');
    }
    
    public function testAdminCanAccess ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->admin)
                ->get(route('admin.dashboard'));
        $response
                ->assertStatus(200);
    }
    
    public function testAnonymousNotCanAccess ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.dashboard'));
        $response
                ->assertStatus(403);
    }
    
    public function testUserNotCanAccess ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.dashboard'));
        $response
                ->assertStatus(403);
    }
}
