<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;
use LaravelLocalization;

use App\User;
use App\App;
use App\AppLocale;
use App\AppImage;

use App\Http\Controllers\Admin\AppImageController;

class AppAdminTest extends TestCase
{
    use DatabaseMigrations;

    protected $apps, $appCurrent, $user, $locale, $usersPublic;

    protected function setUp ()
    {
        parent::setUp();
        
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'public']);
        
        $this->user = factory(User::class)->create()->assignRole('admin');
        $this->usersPublic = factory(User::class, 10)->create()->each(function ($user) {
            $user->assignRole('public');
        });

        $this->apps = factory(App::class, 3)->create()->each(function ($app) {
            factory(AppLocale::class)->create([
                'lang' => 'en',
                'app_id' => $app->id
            ]);

            factory(AppLocale::class)->create([
                'lang' => 'es',
                'app_id' => $app->id
            ]);

            factory(AppImage::class, 3)->create([
                'app_id' => $app->id
            ]);
        });

        $this->appCurrent = $this->apps[0];
        $this->appCurrent->users()->sync($this->usersPublic, [ 'active' => true ]);
        $this->locale = LaravelLocalization::getCurrentLocale();
    }

    public function testSeeViewIndex ()
    {
        $this->withExceptionHandling();

        /******* ERRORS *******/
        $response =
        	$this
                ->get(route('admin.apps'));
        
        $response
                ->assertStatus(403);
        
        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.apps'));
        
        $response
                ->assertStatus(200);

        foreach ( $this->apps AS $app ) {
        	$appLocale = AppLocale::where('lang', $this->locale)->get()->first();

        	$response
        		->assertSee($app->version)
        		->assertSee($app->type)
        		->assertSee($app->vue_component)
        		->assertSee($appLocale->title)
        		->assertSee($appLocale->description);
        }
    }

    public function testStoreApp ()
    {
        $this->withExceptionHandling();
        
        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.store'), []);
        
        $response
                ->assertStatus(500);

        /******* CREATE TEST IMAGE *******/
        $imagePath = Storage::disk( 'public' )->putFile(
            AppImageController::getPathTmp(),
            UploadedFile::fake()->image('random.jpg'),
            'public'
        );

        $imageNew = [
            'id' => null,
            'title' => 'test',
            'src' => Storage::url($imagePath),
            'priority' => 100
        ];
        
        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.store'), [
                    'app' => $this->appCurrent,
                    'locales' => $this->appCurrent->locales()->get(),
                    'images' => [ $imageNew ]
                ]);
        
        $response
                ->assertStatus(200)
                ->assertJsonStructure([ 'app', 'routes' ]);

        $imageInfo = pathinfo(Storage::url($imagePath));
        Storage::disk('public')->assertExists( AppImageController::getPathWeb( json_decode($response->getContent())->app->id ) . '/' . $imageInfo['basename'] );

        /******* REMOVE TEST IMAGE *******/
        Storage::disk('public')->delete( AppImageController::getPathWeb( json_decode($response->getContent())->app->id ) . '/' . $imageInfo['basename'] );
    }

    public function testUpdateApp ()
    {
        $this->withExceptionHandling();

        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.update', $this->appCurrent->id), []);
        
        $response
                ->assertStatus(500);

        /******* CREATE TEST IMAGE *******/
        $imagePath = Storage::disk( 'public' )->putFile(
            AppImageController::getPathTmp(),
            UploadedFile::fake()->image('random.jpg'),
            'public'
        );

        $imageNew = [
            'id' => null,
            'title' => 'test',
            'src' => Storage::url($imagePath),
            'priority' => 100
        ];
        
        /******* SUCCESS *******/
        // Assert with push image
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.update', $this->appCurrent->id), [
                    'app' => $this->appCurrent,
                    'locales' => $this->appCurrent->locales()->get(),
                    'images' => [ $imageNew ]
                ]);

        $response
                ->assertStatus(200)
                ->assertJsonStructure([ 'app' ]);

        $imageInfo = pathinfo(Storage::url($imagePath));
        Storage::disk('public')->assertExists( AppImageController::getPathWeb( $this->appCurrent->id ) . '/' . $imageInfo['basename'] );

        // ASsert with remove image
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.update', $this->appCurrent->id), [
                    'app' => $this->appCurrent,
                    'locales' => $this->appCurrent->locales()->get(),
                    'images' => []
                ]);

        $response
                ->assertStatus(200)
                ->assertJsonStructure([ 'app' ]);

        $imageInfo = pathinfo(Storage::url($imagePath));
        Storage::disk('public')->assertMissing( AppImageController::getPathWeb( $this->appCurrent->id ) . '/' . $imageInfo['basename'] );
    }

    public function testUploadAppImage ()
    {
        $this->withExceptionHandling();

        /******* ERRORS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app-images.upload', $this->appCurrent->id))
                ->assertStatus(302);
        
        /******* SUCCESS *******/
        $response = $this
            ->actingAs($this->user)
            ->post(route('admin.app-images.upload'), [ 'image' => UploadedFile::fake()->image('random.jpg') ])
            ->assertStatus(200)
            ->assertJsonStructure([ 'res', 'data']);
        
        $imageInfo = pathinfo(json_decode($response->getContent())->data->image);
        Storage::disk('public')->assertExists( AppImageController::getPathTmp() . '/' . $imageInfo['basename'] );

        /******* REMOVE TEST IMAGE *******/
        Storage::disk('public')->delete( AppImageController::getPathTmp() . '/' . $imageInfo['basename'] );
    }

    public function testDestroyApp ()
    {
        $this->withExceptionHandling();

        /******* CREATE TEST IMAGE *******/
        $imagePath = Storage::disk( 'public' )->putFile(
            AppImageController::getPathWeb( $this->appCurrent->id ),
            UploadedFile::fake()->image('random.jpg'),
            'public'
        );

        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.app.destroy', $this->appCurrent->id))
                ->assertStatus(200)
                ->assertExactJson([true]);


        Storage::disk('public')->assertMissing(  AppImageController::getPathWeb( $this->appCurrent->id ) . '/random.jpg' );
    }

    public function testSeeViewIndexPrivateUsers ()
    {
        $this->withExceptionHandling();

       /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->get(route('admin.apps.users'))
                ->assertStatus(200);
    }

    public function testSync ()
    {
        $this->withExceptionHandling();

        /******* ERROR *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.apps.users.sync', $this->appCurrent->id), [])
                ->assertStatus(500);

        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.apps.users.sync', $this->appCurrent->id), [
                    'user' => $this->usersPublic[0],
                    'active' => true
                ])
                ->assertStatus(200)
                ->assertJsonStructure([ 'users' ]);
    }

    public function testRevoke ()
    {
        $this->withExceptionHandling();

        /******* ERROR *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.apps.users.revoke', $this->appCurrent->id), [])
                ->assertStatus(500);

        /******* SUCCESS *******/
        $response = $this
                ->actingAs($this->user)
                ->post(route('admin.apps.users.revoke', $this->appCurrent->id), [
                    'user' => $this->usersPublic[0]
                ])
                ->assertStatus(200)
                ->assertExactJson([true]);
    }
}
