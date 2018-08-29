<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run ()
    {
        $this->call([
            UsersTableSeeder::class,
            CommentsTableSeeder::class,
            PagesTableSeeder::class,
            MenusTableSeeder::class,
            AppsTableSeeder::class,
            MessagesTableSeeder::class
        ]);
    }

}
