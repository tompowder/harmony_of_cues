<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('infos')->insert(
        [
            [
                'id' => 1,
                'info_type' => 'other',
                'info_condition' => 'pristine',
                'info_title' => 'elenent fire',
                'info_text' => 'fire is strong aganst metal, nature and none \n and is weak aganst water'
            ] 
        ]);
    }
}
