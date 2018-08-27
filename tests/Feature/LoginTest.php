<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\Models\Core\User;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $admin, $user;
    protected $password = 'secret1!';
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'public']);
        
        $this->admin = factory(User::class)->create([
            'email' => config('mail.from')['address'],
            'password' => Hash::make($this->password)
        ])->assignRole('admin');
        
        $this->user = factory(User::class)->create([
            'email' => 'public@example.com',
            'password' => Hash::make($this->password)
        ])->assignRole('public');
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
        
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }
    
    public function testLoadLoginAdminLogged ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->admin)
                ->get(route('login'));
        $response
                ->assertStatus(302)
                ->assertRedirect(route('admin.dashboard'));
    }
    
    public function testLoadLoginUserLogged ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('login'));
        $response
                ->assertStatus(302)
                ->assertRedirect(route('profile'));
    }
    
    public function testFormLoginErrors ()
    {
        $this->withExceptionHandling();
        
        $response = $this->post(route('login'), []);
        $response->assertSessionHasErrors('email');
        
        $response = $this->post(route('login'), ['email' => 'wrongmail@mail.com']);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('home'));
        
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => 'worngpassword']);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('home'));
        
        $response = $this->post(route('login'), ['email' => $this->user->email]);
        $response->assertSessionHasErrors('password');
    }
    
    public  function testFormLoginAdmin ()
    {
        $this->withExceptionHandling();
        
        $response = $this->post(route('login'), ['email' => $this->admin->email, 'password' => $this->password]);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('admin.dashboard'));
    }
    
    public function testFormLoginUser ()
    {
        $this->withExceptionHandling();
        
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password]);
        $response
            ->assertStatus(302)
            ->assertRedirect(route('profile'));
    }
}
