<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\PlayerService;
use PHPUnit\Framework\TestCase;

class PlayerServiceTest extends TestCase {

    var $service;

    function PlayerServiceTest() {
        $this->PHPUnit_TestCase();
    }

    function setUp() {
        $this->service = new PlayerService();
    }

    protected function tearDown() {
        unset($this->service);
    }

    function testGeneratePlayers() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $this->assertEquals(25, sizeof($result));
    }

    function testGenerateAttributesUniquenessTotalScores() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $totalAttributeScores = (array_map(function ($player) {
                    return $player->getTotalAttributeScore();
                }, $result
        ));
        $this->assertEquals(25, sizeof(array_unique($totalAttributeScores)));
    }

    function testGenerateAttributesMaxTotalScores() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $totalAttributeScores = (array_map(function ($player) {
                    return $player->getTotalAttributeScore();
                }, $result
        ));
        $unexpected = array_filter($totalAttributeScores, function ($score) {
            return $score > 100;
        });
        $this->assertEquals(0, sizeof($unexpected));
    }

    function testGenerateSalaries() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $salary = (array_sum(array_map(function ($player) {
                            return $player->getSalary();
                        }, $result
        )));
        $this->assertLessThanOrEqual(500, $salary);
    }

    function testGenerateNames() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $names = array_unique((array_map(function ($player) {
                    return $player->getFullName();
                }, $result
        )));
        $this->assertEquals(25, sizeof($names));
    }

    function testGenerateUniqueIds() {
        $result = $this->service->generatePlayers(10, 15, 500);

        $uniquesId = array_unique((array_map(function ($player) {
                    return $player->getUniqueId();
                }, $result
        )));
        $this->assertEquals(25, sizeof($uniquesId));
    }

}
