<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\Models\Core\User;
use App\Models\Core\App;
use App\Models\Core\Comment;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        $this->user = factory(User::class)->create();
    }

    public function testUserHasApps ()
    {
    	$count = 10;

        Role::create(['name' => 'public']);
        $this->user->assignRole('public');
        
        $apps = factory(App::class, $count)->create();

        $this->user->apps()->sync($apps);

        $this->assertCount($count, $this->user->apps);
        $this->assertInstanceOf(Collection::class, $this->user->apps);
        $this->assertInstanceOf(App::class, $this->user->apps->first());
    }

    public function testUserHasComments ()
    {
    	$count = 10;
        
        $apps = factory(Comment::class, $count)->create([
        	'user_id' => $this->user->id
        ]);

        $this->assertCount($count, $this->user->comments);
        $this->assertInstanceOf(Collection::class, $this->user->comments);
        $this->assertInstanceOf(Comment::class, $this->user->comments->first());
    }

    public function testUserIsMe ()
    {
        $this->signIn($this->user)->assertTrue($this->user->isMe());
    }

    public function testUserIsBanned ()
    {
    	$this->user->delete();
    	$this->assertTrue($this->user->isBanned());
    }
}
