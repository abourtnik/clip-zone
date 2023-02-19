<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\View;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Interaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::factory(10)->create()->each(function($user) {
            Video::factory(rand(0, 5))->create(['user_id' => $user->id])->each(function ($video) {
                Comment::factory(rand(0, 5))->create(['video_id' => $video->id])->each(function ($comment) use ($video) {
                    Comment::factory(rand(0, 5))->create(['parent_id' => $comment->id, 'video_id' => $video->id])->each(function ($comment) use ($video) {
                        Interaction::factory(rand(0, 5))->create(['likeable_type' => Comment::class, 'likeable_id' => $comment->id]);
                    });
                    Interaction::factory(rand(0, 10))->create(['likeable_type' => Comment::class, 'likeable_id' => $comment->id]);
                    Report::factory(rand(0, 2))->create(['reportable_type' => Comment::class, 'reportable_id' => $comment->id]);
                });
                Interaction::factory(rand(0, 20))->create(['likeable_type' => Video::class, 'likeable_id' => $video->id]);
                View::factory(rand(0, 30))->create(['video_id' => $video->id]);
                Report::factory(rand(0, 2))->create(['reportable_type' => Video::class, 'reportable_id' => $video->id]);
            });
            Report::factory(rand(0, 2))->create(['reportable_type' => User::class, 'reportable_id' => $user->id]);
            foreach (range(0,20) as $i) {
                $user->subscriptions()->toggle([
                    User::where('id', '!=', $user->id)->inRandomOrder()->first()->id => ['subscribe_at' => fake()->dateTimeBetween('-1 year')]
                ]);
            }
        });
    }
}
