<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Content;
use App\Category;
use App\MenuItem;
use App\PageLocale;
use App\CategoryLocale;
use App\Page;
use App\Menu;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = $this->UsersSeeder();
        $pages = $this->PagesSeeder(['user_id' => $adminUser->id]);

        $this->MenusSeeder(['user_id' => $adminUser->id, 'pages' => $pages]);
    }

    private function UsersSeeder ()
    {
        app()['cache']->forget('spatie.permission.cache');

        Role::create(['name' => 'public']);
        Role::create(['name' => 'admin']);

        $publicUser = factory(User::class, 1)->create()->first()->assignRole('public');
        $adminUser = factory(User::class, 1)->create([
            'email' => config('mail.from')['address'],
            'password' => Hash::make('secret')
        ])->first()->assignRole('admin');

        return $adminUser;
    }

    private function PagesSeeder ($params)
    {
        $locales = [];

        $pages = factory(Page::class, 8)->create([
            'user_id' => $params['user_id']
        ]);

        foreach ($pages AS $page) {
            $boolean = (bool)random_int(0, 1);

            if ($boolean) {
                $locale = factory(PageLocale::class, 1)->create([
                    'user_id' => $params['user_id'],
                    'page_id' => $page->id,
                    'lang' => 'en'
                ])->first();
            }

            if (!$boolean || (bool)random_int(0, 1)) {
                $locale = factory(PageLocale::class, 1)->create([
                    'user_id' => $params['user_id'],
                    'page_id' => $page->id,
                    'lang' => 'es'
                ])->first();
            }

            factory(Content::class, random_int(1, 3))->create(['page_id' => $locale->id]);

            array_push($locales, $locale);
        }

        return $locales;
    }

    private function MenusSeeder ($params)
    {
        $menus = factory(Menu::class, 5)->create([
            'user_id' => $params['user_id']
        ])->toArray();

        foreach ($menus AS $menu) {
            factory(MenuItem::class, random_int(1, 5))->create([
                'user_id' => $menu['user_id'],
                'menu_id' => $menu['id'],
                'lang' => (bool)random_int(0, 1) ? 'en' : 'es',
                'type' => 'internal',
                'page_id' => $params['pages'][random_int(0, COUNT($params['pages'])-1)]['id'],
                'url_external' => null
            ]);

            factory(MenuItem::class, 1)->create([
                'user_id' => $menu['user_id'],
                'menu_id' => $menu['id'],
                'lang' => (bool)random_int(0, 1) ? 'en' : 'es',
                'type' => 'external',
                'url_external' => 'http://www.url.com'
            ]);
        }
    }

}
