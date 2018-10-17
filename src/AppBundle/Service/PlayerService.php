<?php

namespace AppBundle\Service;

use AppBundle\Entity\Substitute;
use AppBundle\Entity\Starter;

class PlayerService {

    public function generatePlayers($starters, $substitutes, $salaryCap) {
        $players = array();
        for ($i = 0; $i < 5; $i++) {
            array_push($players, new Starter("firstName", "lastName", 5, 5, 5));
            array_push($players, new Substitute("firstName", "lastName", 5, 5, 5));
        }
        return $players;
    }

}
