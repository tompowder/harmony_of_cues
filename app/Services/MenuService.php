<?php

namespace App\Services;

class MenuService
{
   public function handleInput(array $offset = ['x' => 0, 'y' => 0]) 
   {
      return ["display" => '', "coordinates" => ''];
   }

   public function clampCoordinates(array $options, array $offset)  
   {
      $currentX  = (int) session()->get('menu_x');
      $currentY  = (int) session()->get('menu_y');
      $coordinates = ["x" => $currentX, "y" => $currentY];

      $coordinates["x"] += $offset['x'];
      $coordinates["y"] += $offset['y'];

      $maxY = count($options) - 1;
  
      if ($maxY < 0) 
      {return ['x' => 0, 'y' => 0];}
  
      $coordinates['y'] = max(0, min($coordinates['y'], $maxY));
  
      $row = $options[$coordinates['y']] ?? [];
  
      $maxX = count($row) - 1;
  
      if ($maxX < 0) 
      {return ['x' => 0, 'y' => 0];}
  
      $coordinates['x'] = max(0, min($coordinates['x'], $maxX));
      
      if (($row[$coordinates['x']] ?? null) === null)
      {
         $x = max(0, $coordinates['x'] - $offset['x']);
         $y = max(0, $coordinates['y'] - $offset['y']);
         return ['x' => $x, 'y' => $y];
      }
  
      return $coordinates;
   }

   public function combineBoxes(array $boxes)
   {
      $maxLines = max(array_map('count', $boxes));
      $lines = [];

      for ($i = 0; $i < $maxLines; $i++)
      {
         $lines[] = implode(' ', array_map(fn($box) => $box[$i], $boxes));
      }

      $lines = array_map(fn(string $line) => str_replace(' ', '&nbsp', $line), $lines);

      return $lines;
   }

   public function createBox(array $content)
   {
      $width = max(array_map('strlen', $content));

      $LeftRichtborder = 2;

      $topBottomBorder = str_repeat('-', $width + $LeftRichtborder);

      $lines = array_map(fn (string $line) => '|' . str_pad($line, $width) . '|', $content);

      array_unshift($lines, $topBottomBorder);
      $lines[] = $topBottomBorder;

      return $lines;
   }

   // alle the types of $replace 
   // :entity :ally :enemy :monster :hero :user :target :move :type
   // :synergy :xp :gold :equipment :level :old_level :new_level
   // 
   // exsampel call
   // processDialogue(['type' => 'encounter', 'id' => 'move_used'], 
   //['user' => 'Jonathan', 'target' => 'goblin', 'move' => 'Tackle', 'damage' => '15'])
   public function processDialogue(array $dialogueId, array $replace = [])
   {
      $dialogue = $this->dialogue($dialogueId['type'], $dialogueId['id']);

      if($replace == [])
      {
         return $dialogue;
      }

      $replaceKeys = array_keys($replace);
      $replaceValues = array_values($replace);
      
      for ($i = 0; count($replace) > $i; $i++)
      {
         $key = ':' . $replaceKeys[$i];
         $value = $replaceValues[$i];

         $dialogue = str_replace($key, $value, $dialogue);
      }

      return $dialogue;

   }

   private function dialogue(string $type, string $id)
   {
      $dialogue =
      [
         'encounter' => 
         [
            'encounter_start' => 'the monster(s) :monster are in the way!',
            'select_action' => 'Select an action.',
            'select_move' => 'Choose a :type move.',
            'select_target' => 'Choose a target using :move on.',
            'no_magic_moves' => ':hero doesn\'t know any magic moves.',
            'no_attack_moves' => ':hero doesn\'t know any attack moves.',
            'insufficient' => 'Not enough :type to use :move.',
            'escape_success' => 'Your party escaped the encounter safely.',
            'escape_failed' => 'Your party couldn\'t escape the encounter!',
            'synergy_activated' => ':synergy synergy activated!',
            'move_used' => ':user used :move on :target, dealing :damage damage.',
            'affinity_advantage' => ':move has an affinity advantage!',
            'affinity_disadvantage' => ':move is at an affinity disadvantage.',
            'enemy_defeated' => 'The :monster was defeated!',
            'encounter_victory' => 'Victory! Your party earned :xp XP.',
            'encounter_defeat' => 'Your party was defeated...',
            'equipment_drop' => 'The :monster dropped :equipment.',
            'gold_gained' => 'Your party received :gold gold.',
            'level_up' => ':hero leveled up from :old_level to :new_level!',
         ],

         'menu' => 
         [
            'move_forgotten' => ':hero forgot :move.',
            'move_learned' => ':hero learned :move!',
            'insufficient_gold' => 'You don\'t have enough gold.',
         ],
      ];

      return $dialogue[$type][$id];
   }
}
