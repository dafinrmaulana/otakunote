<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = [
      [
        "name" => "Anime",
        "slug" => "anime",
        "user_id" => 1,
      ],
      [
        "name" => "Manga",
        "slug" => "manga",
        "user_id" => 1,
      ],
      [
        "name" => "Novel",
        "slug" => "novel",
        "user_id" => 1,
      ],
      [
        "name" => "Manhwa",
        "slug" => "manhwa",
        "user_id" => 1,
      ],
      [
        "name" => "Manhua",
        "slug" => "manhua",
        "user_id" => 1,
      ]
    ];
    $categories2 = [
      [
        "name" => "Anime",
        "slug" => "anime",
        "user_id" => 2,
      ],
      [
        "name" => "Manga",
        "slug" => "manga",
        "user_id" => 2,
      ],
      [
        "name" => "Novel",
        "slug" => "novel",
        "user_id" => 2,
      ],
      [
        "name" => "Manhwa",
        "slug" => "manhwa",
        "user_id" => 2,
      ],
      [
        "name" => "Manhua",
        "slug" => "manhua",
        "user_id" => 2,
      ]
    ];

    foreach ($categories as $category) {
      Category::create($category);
    }

    foreach ($categories2 as $category) {
      Category::create($category);
    }
  }
}
