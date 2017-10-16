<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\User;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $admin;
    protected $user;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'public']);
        
        $this->admin = factory(User::class, 1)->create([
            'email' => config('mail.from')['address'],
            'password' => Hash::make('secret')
        ])->first()->assignRole('admin');
        
        $this->user = factory(User::class, 1)->create([
            'email' => 'public@example.com'
        ])->first()->assignRole('public');
    }
    
    public function testPermissionsUsers ()
    {
        $this->assertTrue(!$this->user->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('public'));
        $this->assertTrue($this->admin->hasRole('admin'));
        $this->assertTrue(!$this->admin->hasRole('public'));
    }
    
    public function testLoadLoginNotLogged ()
    {
        $this->withExceptionHandling();
        
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    public function testLoadLoginAdminLogged ()
    {
        $this->withExceptionHandling();
        $this->signIn($this->admin);
        
        $response = $this->get('/login');
        $response
                ->assertStatus(302)
                ->assertRedirect('dev');
    }
    
    public function testLoadLoginUserLogged ()
    {
        $this->withExceptionHandling();
        $this->signIn($this->user);
        
        $response = $this->get('/login');
        $response
                ->assertStatus(302)
                ->assertRedirect('profile');
    }
}
