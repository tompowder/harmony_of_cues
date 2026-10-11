<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AffinitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('affinities')->insert(
        [
            ['id' => 1, 'name' => 'Fire'],
            ['id' => 2, 'name' => 'Water'],
            ['id' => 3, 'name' => 'Nature'],
            ['id' => 4, 'name' => 'None'],
            ['id' => 5, 'name' => 'Metal']
        ]);

        $effective = 1.50;
        $weak = 0.75;

        DB::table('affinity_relationships')->insert(
        [
            // fire on water
            ['id' => 1, 'affinity_id' => 1, 'target_affinity_id' => 2, 'multiplier' => $weak],
            // fire on metal
            ['id' => 2, 'affinity_id' => 1, 'target_affinity_id' => 5, 'multiplier' => $effective],
            // fire on nature
            ['id' => 3, 'affinity_id' => 1, 'target_affinity_id' => 3, 'multiplier' => $effective],
            // fire on none 
            ['id' => 4, 'affinity_id' => 1, 'target_affinity_id' => 4, 'multiplier' => $effective],

            // water on fire
            ['id' => 5, 'affinity_id' => 2, 'target_affinity_id' => 1, 'multiplier' => $effective],
            // water on nature 
            ['id' => 6, 'affinity_id' => 2, 'target_affinity_id' => 3, 'multiplier' => $weak],
            // water on metal
            ['id' => 7, 'affinity_id' => 2, 'target_affinity_id' => 5, 'multiplier' => $weak],

            // nature on fire
            ['id' => 8, 'affinity_id' => 3, 'target_affinity_id' => 1, 'multiplier' => $weak],
            // nature on water 
            ['id' => 9, 'affinity_id' => 3, 'target_affinity_id' => 2, 'multiplier' => $effective],
            // nature on metal 
            ['id' => 10, 'affinity_id' => 3, 'target_affinity_id' => 5, 'multiplier' => $effective],

            // none on metal
            ['id' => 11, 'affinity_id' => 4, 'target_affinity_id' => 5, 'multiplier' => $weak],
            // none on nature
            ['id' => 12, 'affinity_id' => 4, 'target_affinity_id' => 3, 'multiplier' => $effective],

            // metal on water
            ['id' => 13, 'affinity_id' => 5, 'target_affinity_id' => 2, 'multiplier' => $effective],
            // metal on nature
            ['id' => 14, 'affinity_id' => 5, 'target_affinity_id' => 3, 'multiplier' => $weak],
            // metal on fire
            ['id' => 16, 'affinity_id' => 5, 'target_affinity_id' => 1, 'multiplier' => $weak],
        ]);
    }
}
