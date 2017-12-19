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

class DatabaseSeeder extends Seeder
{
    public function run ()
    {
        /******** USERS ********/
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'public']);
        Role::create(['name' => 'admin']);

        factory(User::class, 1)->create()->first()->assignRole('public');
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
                
                factory(Content::class)->create([
                    'page_locale_id' => $pageLocale->id
                ]);
            }
            
            if (!$random || (bool)random_int(0, 1)) {
                $pageLocale = factory(PageLocale::class)->create([
                    'lang' => 'es',
                    'page_id' => $page->id
                ]);
                
                factory(Content::class)->create([
                    'page_locale_id' => $pageLocale->id
                ]);
            }
        });
        
        /******** MENUS ********/
        factory(Menu::class, 2)->create();
        factory(MenuItem::class, 10)->create();
    }

}
