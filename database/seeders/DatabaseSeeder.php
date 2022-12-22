<?php

namespace Database\Seeders;

use App\Models\View;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(30)->create()->each(function($user) {
            $user->videos()->saveMany(Video::factory(rand(0, 10))->create([
                'user_id' => $user->id
            ]))->each(function($video) {
                $video->comments()->saveMany(Comment::factory(rand(0, 10))->create([
                    'video_id' => $video->id
                ]));
                $video->interactions()->saveMany(Like::factory(rand(0, 100))->create([
                    'likeable_type' => Video::class,
                    'likeable_id' => $video->id,
                ]));
                $video->views()->saveMany(View::factory(rand(0, 100))->create([
                    'video_id' => $video->id
                ]));
            });
            foreach (range(0,20) as $i) {
                $user->subscriptions()->toggle(User::where('id', '!=', $user->id)->inRandomOrder()->first());
            }
        });
    }
}
