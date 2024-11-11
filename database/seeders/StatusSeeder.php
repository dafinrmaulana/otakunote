<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $statuses = [
      [
        "name" => "On Going",
        "slug" => "on-going",
        "color" => "#7c3aed",
        "user_id" => 1,
      ],
      [
        "name" => "End",
        "slug" => "end",
        "color" => "#ef4444",
        "user_id" => 1,
      ],
      [
        "name" => "On Hold",
        "slug" => "on-hold",
        "color" => "#f59e0b",
        "user_id" => 1,
      ],
      [
        "name" => "Dropped",
        "slug" => "drpped",
        "color" => "#ea580c",
        "user_id" => 1,
      ],
      [
        "name" => "Hiatus",
        "slug" => "hiatus",
        "color" => "#14b8a6",
        "user_id" => 1,
      ],
    ];

    $statuses2 = [
      [
        "name" => "On Going",
        "slug" => "on-going",
        "user_id" => 2,
      ],
      [
        "name" => "End",
        "slug" => "end",
        "user_id" => 2,
      ],
      [
        "name" => "On Hold",
        "slug" => "on-hold",
        "user_id" => 2,
      ],
      [
        "name" => "Dropped",
        "slug" => "drpped",
        "user_id" => 2,
      ],
      [
        "name" => "Hiatus",
        "slug" => "hiatus",
        "user_id" => 2,
      ],
    ];

    foreach ($statuses as $status) {
      Status::create($status);
    }

    foreach ($statuses2 as $status) {
      Status::create($status);
    }
  }
}
