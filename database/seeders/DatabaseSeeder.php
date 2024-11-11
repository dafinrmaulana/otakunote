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
      'name' => 'Dafi',
      'email' => 'admin@otaku.com',
      'password' => bcrypt('admin')
    ]);
    User::create([
      'name' => 'User',
      'email' => 'user@otaku.com',
      'password' => bcrypt('user')
    ]);

    $this->call([
      StatusSeeder::class,
      CategorySeeder::class
    ]);
  }
}
