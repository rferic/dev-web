<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\MenuItem;
use App\PageLocale;
use App\Page;
use App\Menu;
use App\Content;
use App\Comment;
use App\App;
use App\AppImage;

class DatabaseSeeder extends Seeder
{
    public function run ()
    {
        /******** USERS ********/
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'public']);
        Role::create(['name' => 'admin']);

        $userPublic = factory(User::class, 1)->create()->first()->assignRole('public');
        $userPublic->each(function ($user) {
            /******** COMMENTS ********/
            factory(Comment::class, 5)->create()->each(function ($comment) use ($user) {
                $user->comments()->save($comment);
            });
        });
        factory(User::class, 1)->create([
            'email' => config('mail.from')['address'],
            'password' => Hash::make('secret')
        ])->first()->assignRole('admin');
        
        /******** PAGES ********/
        factory(Page::class, 8)->create()->each(function ($page) {
            $random = (bool)random_int(0, 1);
            
            if ($random) {
                $pageLocale = factory(PageLocale::class)->create([
                    'lang' => 'en',
                    'page_id' => $page->id
                ]);
                
                factory(Content::class, 3)->create([
                    'page_locale_id' => $pageLocale->id
                ]);
            }
            
            if (!$random || (bool)random_int(0, 1)) {
                $pageLocale = factory(PageLocale::class)->create([
                    'lang' => 'es',
                    'page_id' => $page->id
                ]);
                
                factory(Content::class, 3)->create([
                    'page_locale_id' => $pageLocale->id
                ]);
            }
        });
        
        /******** MENUS ********/
        factory(Menu::class, 2)->create();
        factory(MenuItem::class, 10)->create();


        /******** APPS ********/
        $apps = factory(App::class, 5)->create()->each(function ($app) {
            factory(AppImage::class, 3)->create([
                'app_id' => $app->id
            ]);
        });
        $userPublic->apps()->sync($apps);
    }

}
