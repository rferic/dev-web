<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

use App\Models\Core\User;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected $user, $user2;
    protected $password = 'secret1!';
    protected $email1 = 'test@test.com';
    protected $email2 = 'test2@test.com';
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create([
            'email' => $this->email1,
            'password' => Hash::make($this->password)
        ])->assignRole('admin');
        $this->user2 = factory(User::class)->create([ 'email' => $this->email2 ]);
    }

    public function testSeeViewIndex ()
    {
        $this->withExceptionHandling();
        
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.profile'))
                ->assertStatus(200)
                ->assertSee($this->user->email)
                ->assertSee($this->user->name);
    }

    public function testSeeViewPostUpdate ()
    {
        $this->withExceptionHandling();

        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.update'), [])
                ->assertStatus(500);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.update'), [
                    'profile' => []
                ])
                ->assertStatus(500);

        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.update'), [
                    'profile' => [
                        'email' => 'test3@test.com',
                        'name' => 'Test Tester'
                    ]
                ])
                ->assertStatus(200)
                ->assertExactJson([ 'result' => true ]);
    }  

    public function testSeeViewPostReset ()
    {
        $this->withExceptionHandling();

        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [])
                ->assertStatus(500);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [
                    'password' => 'secret1!',
                    'password_confirmation' => 'secret',
                    'passwordCurrent' => $this->password
                ])
                ->assertStatus(302);
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [
                    'password' => 'secret',
                    'password_confirmation' => 'secret',
                    'passwordCurrent' => $this->password
                ])
                ->assertStatus(302);
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [
                    'password' => 'secret1!',
                    'password_confirmation' => 'secret',
                    'passwordCurrent' => $this->password
                ])
                ->assertStatus(302);

        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [
                    'password' => 'secret',
                    'password_confirmation' => 'secret',
                    'passwordCurrent' => 'secret'
                ])
                ->assertStatus(200)
                ->assertExactJson([ 'result' => false ]);
        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.reset'), [
                    'password' => 'secret2!',
                    'password_confirmation' => 'secret2!',
                    'passwordCurrent' => $this->password
                ])
                ->assertStatus(200)
                ->assertExactJson([ 'result' => true ]);
    }

    public function testSeePostEmailIsFree ()
    {
        $this->withExceptionHandling();
        
        /******* ERRORS *******/        
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.emailIsFree'), [])
                ->assertStatus(500);
        
        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.emailIsFree'), [ 'value' => $this->email2 ])
                ->assertStatus(200)
                ->assertExactJson(['result' => false]);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.emailIsFree'), [ 'value' => $this->email1 ])
                ->assertStatus(200)
                ->assertExactJson(['result' => true]);

        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.profile.emailIsFree'), [ 'value' => 'hello@test.com' ])
                ->assertStatus(200)
                ->assertExactJson(['result' => true]);
    }
}
