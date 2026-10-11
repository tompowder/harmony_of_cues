<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('maps')->insert(
        [
            [
                'id' => 1,
                'name' => 'alpha',
                'map_data' => "----------------------\n|S       .          $|\n|     ---------      |\n|   --|   .   |      |\n|   |>#       |    . |\n|   --|  .    |.. . .|\n|     ----    |  .   |\n|.    .    .  |      |\n|I|   .    R  |      |\n-----------------<----"
            ],
            [
                'id' => 2,
                'name' => 'beta',
                'map_data' => "|---<---------------------------|\n|       |               .       |\n|       |   #           .       |\n|       |               .       |\n|.......|----------------.......|\n|       |----------------       |\n|       .       .       .       |\n$       .       .       .       |\n|---S---------------------------|\n"
            ],
            [
                'id' => 3,
                'name' => 'gamma',
                'map_data' => "------------------------------S--\n|           .       .           |\n|           .       .           |\n|         --------------R.......|\nI       | |-----| | | | |       |\n|       $ |--------------       |\n|       | |----         .       |\n|       | | >           .       |\n----<----------------------------\n"
            ]
        ]);


        // new map design wear x is /2 so the player feel like there moving the same distance in the x and y direction.
        // and the player moves x 2 places and for y move 1 place
        //[
        //   "- - - - - - - - - - - -\n",
        //   "| S       .          $|\n",
        //   "|     - - - - -       |\n",
        //   "|   - |   .   |       |\n",
        //   "|   | > #     |     . |\n",
        //   "|   - |  .    | .     |\n",
        //   "|     - - - . |   .   |\n",
        //   "| .     .   . |       |\n",
        //   "| I |   .   R |       |\n",
        //   "- - - - - - - - - - - -\n"
        //];

        //[
        //   "- - < - - - - - - - - - - - - - -\n",
        //   "|       |               .       |\n",
        //   "|       |   #           .       |\n",
        //   "|       |               .       |\n",
        //   "| . . . | - - - - - - - - . . . |\n",
        //   "|       | - - - - - - - -       |\n",
        //   "|       .       .       .       |\n",
        //   "$       .       .       .       |\n",
        //   "- - S - - - - - - - - - - - - - -\n"
        //];

        //[
        //   "- - - - - - - - - - - - - - - S -\n",
        //   "|           .       .           |\n",
        //   "|           .       .           |\n",
        //   "|         - - - - - - - R . . . |\n",
        //   "I       | | - - | | | | |       |\n",
        //   "|       $ | - - - - - - -       |\n",
        //   "|       | | - -         .       |\n",
        //   "|       | | >           .       |\n",
        //   "- - < - - - - - - - - - - - - - -\n"
        //];
    }
}
