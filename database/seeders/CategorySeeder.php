<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $categories = [
            ['title' => 'Autos & Vehicles', 'icon' => 'car'],
            ['title' => 'Comedy', 'icon' => 'masks-theater'],
            ['title' => 'Education', 'icon' => 'book'],
            ['title' => 'Entertainment', 'icon' => 'champagne-glasses'],
            ['title' => 'Film & Animation', 'icon' => 'film'],
            ['title' => 'Gaming', 'icon' => 'gamepad', 'in_menu' => true, 'position' => 2],
            ['title' => 'Howto & Style', 'icon' => 'gamepad', 'in_menu' => true],
            ['title' => 'Music', 'icon' => 'music', 'in_menu' => true, 'position' => 1],
            ['title' => 'News & Politics', 'icon' => 'newspaper', 'in_menu' => true, 'position' => 3],
            ['title' => 'Nonprofits & Activism', 'icon' => 'building-columns'],
            ['title' => 'People & Blogs', 'icon' => 'star'],
            ['title' => 'Pets & Animals', 'icon' => 'paw', 'in_menu' => true, 'position' => 12],
            ['title' => 'Science & Technology', 'icon' => 'flask', 'in_menu' => true, 'position' => 13],
            ['title' => 'Sports', 'icon' => 'futbol', 'in_menu' => true, 'position' => 4],
            ['title' => 'Travel & Events', 'icon' => 'suitcase', 'in_menu' => true, 'position' => 15],
            ['title' => 'Fashion & Beauty', 'icon' => 'bag-shopping', 'in_menu' => true, 'position' => 16]
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }
    }
}
