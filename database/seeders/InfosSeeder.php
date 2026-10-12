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
            // Element information
            [
                'id' => 1,
                'info_type' => 'other',
                'info_condition' => 'pristine',
                'info_title' => 'elenent fire',
               'info_text' => "Strong against: Metal, Nature, and None.\nWeak against: Water."
            ],
            [
               'id' => 2,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'elenent water',
               'info_text' => "Strong against: Fire.\nWeak against: Nature and Metal."
            ],
            [
               'id' => 3,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'elenent nature',
               'info_text' => "Strong against: Water and Metal.\nWeak against: Fire."
            ],
            [
               'id' => 4,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'none elenent',
               'info_text' => "Strong against: Nature.\nWeak against: Fire."
            ],
            [
               'id' => 5,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'elenent metal',
               'info_text' => "Strong against: Water.\nWeak against: Fire and Nature."
            ],
            [
               'id' => 6,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'goblin',
               'info_text' => "A weak monster that can use simple magic."
            ],
            [
               'id' => 7,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'golden goblin',
               'info_text' => "A rare Goblin variant with the Metal affinity.\nIt rewards you with large amounts of Gold and XP, but flees after 5 turns.\nMagic is recommended due to its high Defense."
            ],
            [
               'id' => 8,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'slime',
               'info_text' => "The weakest monster you can encounter"
            ],
            [
               'id' => 9,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'metal slime',
               'info_text' => "A rare Slime variant with the Metal affinity.\nIt rewards you with large amounts of Gold and XP. However, it will do whatever it takes to protect itself, including setting up its Guard.\nUse powerful attacks early to defeat it before it can establish its defenses."
            ],
            [
               'id' => 10,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'fire slime',
               'info_text' => "A Slime variant that has gained the power of Fire.\nAvoid relying on physical attacks, as this monster can counter them with its special move, Fiery Retribution. This attack ignores Defense."
            ],
            [
               'id' => 11,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'forest wolf',
               'info_text' => "A wolf with the Nature affinity that becomes more dangerous when fighting in a pack.\nEach additional wolf in the encounter increases the pack's Attack by 30%.\nFocus on defeating the wolves one at a time to reduce the pack's combined strength."
            ],
            [
               'id' => 12,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'water djinn',
               'info_text' => "A rare monster with the Water affinity that specializes in powerful magic. \n Defeating it can reward you with valuable equipment."
            ],
            [
               'id' => 13,
               'info_type' => 'boss',
               'info_condition' => 'pristine',
               'info_title' => 'infernal knight',
               'info_text' => "A powerful monster with the Fire affinity.\nAs its HP falls below 40%, its Attack increases by 30% and it becomes more reckless.\nIt can use the synergy move Draco Inferno, which reduces your Defense by 25% for 2 turns."
            ],
            [
               'id' => 14,
               'info_type' => 'monster',
               'info_condition' => 'pristine',
               'info_title' => 'dummy',
               'info_text' => "A training dummy used to test your strength."
            ],
            [
               'id' => 15,
               'info_type' => 'synergy',
               'info_condition' => 'pristine',
               'info_title' => 'Holy Water',
               'info_text' => "Combination: Heal + Water Blast.\nEffect: Heals allies and damages enemies."
            ],
            [
               'id' => 16,
               'info_type' => 'synergy',
               'info_condition' => 'pristine',
               'info_title' => 'Burning Catastrophe',
               'info_text' => "Combination: Nature's Wrath + Flame Point.\nEffect: Damages enemies."
            ],
            [
               'id' => 17,
               'info_type' => 'synergy',
               'info_condition' => 'pristine',
               'info_title' => 'Draco Inferno',
               'info_text' => "Combination: Flame Slash + Ashen Plate equiped.\nEffect: Reduces the target's Defense by 25% for 2 turns."
            ],
            [
               'id' => 18,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'alpha map',
               'info_text' => "No particularly powerful monsters inhabit this area."
            ],
            [
               'id' => 19,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'beta map',
               'info_text' => ""
            ],
            [
               'id' => 20,
               'info_type' => 'other',
               'info_condition' => 'pristine',
               'info_title' => 'gamma map',
               'info_text' => ""
            ],
            [
               'id' => 21,
               'info_type' => 'monster',
               'info_condition' => 'wild',
               'info_title' => 'golden goblin',
               'info_text' => "There is a rare chance of finding a Golden Goblin in these woods.\nIt will flee after a while, but defeat it before it escapes to earn a rich reward."
            ],
            [
               'id' => 22,
               'info_type' => 'monster',
               'info_condition' => 'wild',
               'info_title' => 'metal slime',
               'info_text' => "There is a rare chance of finding a Metal Slime in these woods.\nIts defenses are formidable, but defeating it will earn you a rich reward."
            ],
            [
               'id' => 23,
               'info_type' => 'monster',
               'info_condition' => 'wild',
               'info_title' => 'water djinn',
               'info_text' => "There is a rare chance of finding a Water Djinn in these woods.\nBeware its powerful magic. Defeating it may reward you with valuable equipment."
            ],
            [
               'id' => 24,
               'info_type' => 'boss',
               'info_condition' => 'wild',
               'info_title' => 'mystery knight',
               'info_text' => "The knight's flames burn brightest when it is close to death.\nYou will find him at the end of the path."
            ]
         ]
      );
    }
}
