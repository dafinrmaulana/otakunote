<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Admin',
      'email' => 'admin@otaku.note',
      'password' => bcrypt('admin')
    ]);
    User::create([
      'name' => 'User',
      'email' => 'user@otaku.note',
      'password' => bcrypt('user')
    ]);

    // $this->call([
    //   StatusSeeder::class,
    //   CategorySeeder::class
    // ]);
  }
}
