<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::query()->updateOrCreate(
      ['email' => 'admin@nu.edu.ph'],
      [
        'firstname' => 'System',
        'lastname' => 'Admin',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'email_verified_at' => now(),
      ]
    );
  }
}
