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
    public function run()
    {
        $categories = [
            [
                'title' => 'Autos & Vehicles',
                'icon' => 'car',
            ],
            [
                'title' => 'Comedy',
                'icon' => 'masks-theater'
            ],
            [
                'title' => 'Education',
                'icon' => 'book',
                'in_menu' => true,
                'position' => 5,
                'background' => 'education.png'
            ],
            [
                'title' => 'Entertainment',
                'icon' => 'champagne-glasses'
            ],
            [
                'title' => 'Film & Animation',
                'icon' => 'film'
            ],
            [
                'title' => 'Gaming',
                'icon' => 'gamepad',
                'in_menu' => true,
                'position' => 2,
                'background' => 'gaming.png'
            ],
            [
                'title' => 'Howto & Style',
                'icon' => 'gamepad'
            ],
            [
                'title' => 'Music',
                'icon' => 'music',
                'in_menu' => true,
                'position' => 1,
                'background' => 'music.png'
            ],
            [
                'title' => 'News & Politics',
                'icon' => 'newspaper',
                'in_menu' => true,
                'position' => 3,
                'background' => 'news.png'
            ],
            [
                'title' => 'Nonprofits & Activism',
                'icon' => 'building-columns'
            ],
            [
                'title' => 'People & Blogs',
                'icon' => 'star'
            ],
            [
                'title' => 'Pets & Animals',
                'icon' => 'paw',
                'in_menu' => true,
                'position' => 12,
                'background' => 'pets.png'
            ],
            [
                'title' => 'Science & Technology',
                'icon' => 'flask',
                'in_menu' => true,
                'position' => 13,
                'background' => 'science.png'
            ],
            [
                'title' => 'Sports',
                'icon' => 'futbol',
                'in_menu' => true,
                'position' => 4,
                'background' => 'sports.png'
            ],
            [
                'title' => 'Travel & Events',
                'icon' => 'suitcase',
                'in_menu' => true,
                'position' => 15,
                'background' => 'travel.png'
            ],
            [
                'title' => 'Fashion & Beauty',
                'icon' => 'bag-shopping',
                'in_menu' => true,
                'position' => 16,
                'background' => 'fashion.png'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
