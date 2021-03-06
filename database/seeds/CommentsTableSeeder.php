<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use App\Models\Core\User;
use App\Models\Core\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::role('public')->get()->first();

        factory(Comment::class, 5)->create()->each(function ($comment) use ($user) {
            $user->comments()->save($comment);
        });
    }
}
