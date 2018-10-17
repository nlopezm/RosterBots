<?php

namespace AppBundle\Service;

use AppBundle\Entity\Player;
use AppBundle\Entity\Starter;
use AppBundle\Entity\Substitute;

class PlayerService {

    public function generatePlayers($starters, $substitutes, $salaryCap, $existingNames = [], $existingUIds = []) {
        $players = array();
        $n = $starters + $substitutes;
        $names = $this->generateNames($n, $existingNames);
        $attributes = $this->generateAttributes($n);
        $salaries = $this->generateSalaries($n, $salaryCap);
        for ($i = 0; $i < $n; $i++) {
            while (!isset($uniqueId) || in_array($uniqueId, $existingUIds))
                $uniqueId = strtoupper(substr(uniqid(), -6));
            $existingUIds[] = $uniqueId;
            if ($i < $starters)
                array_push($players, new Starter($uniqueId, $names[$i]['firstName'], $names[$i]['lastName'], $attributes[$i]['speed'], $attributes[$i]['strength'], $attributes[$i]['agility'], $salaries[$i]));
            else
                array_push($players, new Substitute($uniqueId, $names[$i]['firstName'], $names[$i]['lastName'], $attributes[$i]['speed'], $attributes[$i]['strength'], $attributes[$i]['agility'], $salaries[$i]));
        }
        return $players;
    }

    private function generateAttributes($n) {
        $attributes = array();
        for ($i = 0; $i < $n; $i++) {
            $attribute = array();
            /*
             * - Each attribute (speed, strength, agility) MUST be defined as an integer value greater than one (1)
             * - The combined total of the attributes , "total attribute score" for a given player bot MUST not exceed 100 and 
             * each "total attribute score" MUST be unique across all player bots on the same roster
             */
            while (!isset($attribute['total']) || in_array($attribute['total'], array_column($attributes, 'total'))) {
                $attribute['total'] = 0;
                $attribute['speed'] = rand(2, 100 - 4);
                $attribute['strength'] = rand(2, 100 - $attribute['speed'] - 2);
                $attribute['agility'] = rand(2, 100 - $attribute['speed'] - $attribute['strength']);
                $attribute['total'] = array_sum($attribute);
            }
            array_push($attributes, $attribute);
        }
        return $attributes;
    }

    private function generateSalaries($n, $salaryCap) {
        $salaries = array();

        // Minimum salary definen randomly by own criteria
        $minSalary = rand(rand(1, floor($salaryCap / $n) / 4), floor($salaryCap / $n) / 2);

        // The maximum salary has to be different from the minimum because otherwise every player would earn the same
        while (!isset($maxSalary) || $minSalary === $maxSalary)
            $maxSalary = rand($minSalary, floor($salaryCap / $n));
        for ($i = 0; $i < $n; $i++) {
            $salary = rand($minSalary, $maxSalary);
            array_push($salaries, $salary);
        }
        return $salaries;
    }

    private function generateNames($n, $existingNames = []) {
        $names = array();
        $possibleCombinations = sizeof(Player::FIRST_NAMES) * sizeof(Player::LAST_NAMES);

        for ($i = 0; $i < $n; $i++) {
            while (!isset($name) || in_array($name, $existingNames)) {
                $name = Player::FIRST_NAMES[array_rand(Player::FIRST_NAMES)] . ' ' . Player::LAST_NAMES[array_rand(Player::LAST_NAMES)];
                // If there is no more combinations, adds a number
                if ($possibleCombinations <= sizeof($existingNames))
                    $name .= rand(1, 100);
            }
            array_push($names, $name);
            array_push($existingNames, $name);
        }

        return array_map(function($string) {
            $name['firstName'] = explode(" ", $string)[0];
            $name['lastName'] = explode(" ", $string)[1];
            return $name;
        }, $names);
    }

}
