<?php

namespace Database\Seeders;

use App\Models\Article;
use Database\Factories\VideoFactory;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(100)->create()->each(function($user) {
            $user->videos()->saveMany(Video::factory(rand(0, 10))->create([
                'user_id' => $user->id
            ]))->each(function($video) {
                $video->comments()->saveMany(Comment::factory(rand(0, 10))->create([
                    'video_id' => $video->id
                ]));
            });
        });



        //User::factory(100)->create();

         /*User::factory()->create([
             'username' => 'anton',
             'email' => 'anton.bourtnik@hotmail.fr',
             'email_verified_at' => now(),
             'password' => Hash::make('0000'),
             'remember_token' => Str::random(10),
             'is_admin' => true
         ]);*/

        //Article::factory(100000)->create();

        //Video::factory(100)->create();
    }
}
