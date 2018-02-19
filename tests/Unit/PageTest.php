<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

use Spatie\Permission\Models\Role;

use App\User;

class PageTest extends TestCase
{
    use DatabaseMigrations;
    
    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
    }
}
