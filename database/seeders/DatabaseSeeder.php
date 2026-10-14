<?php

namespace Database\Seeders;

use App\Models\User;
use Dom\Entity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   use WithoutModelEvents;

   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      DB::table('users')->insert([
         'id' => 1,
         'name' => 'test',
         'email' => 'test@test.com',
         'password' => bcrypt('test')
      ]);

      $this->call([
         AffinitySeeder::class,
         AbilitySeeder::class,
         MoveSeeder::class,
         SynergySeeder::class,
         LevelSchemaSeeder::class,
         EquipmentSeeder::class,
         EntitySeeder::class,
         InfosSeeder::class,
         MapSeeder::class,
         EncounterSeeder::class,
         PointSeeder::class,
         PlayerSeeder::class,
         SavedHeroSeeder::class
      ]);
   }
}
