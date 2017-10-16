<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function testLoadLoginNotLogged ()
    {
        $this->withExceptionHandling();
        
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    public function testLoadLoginAdminLogged ()
    {
        $this->withExceptionHandling();
        $this->signIn();
        
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    public function testLoadLoginUserLogged ()
    {
        $this->withExceptionHandling();
        $this->signIn();
        
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
