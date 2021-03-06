<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use App\Models\Core\Message;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Message::class, 5)->create();
    }
}
