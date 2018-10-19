<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase {

    private $em;

    protected function setUp() {
        static::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testGetPlayerAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues/1/teams/1/players/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetPlayersAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues/1/teams/1/players');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutPlayerAction() {
        $client = static::createClient();
        $body = array('firstName' => 'First name', 'lastName' => 'Last name');
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

    public function testDeletePlayerAction() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/leagues/1/teams/1/players/1');
        $this->em->getFilters()->disable('softdeleteable');
        $player = $this->em->find('AppBundle:Player', 1);
        //Restores the logical removed league
        $player->setDeletedAt(null);
        $this->em->persist($player);
        $this->em->flush();
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

    public function existsPlayermNameActionForNonExistent() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams/1/players?firstName=Non&lastName=Existent');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function existsPlayerNameActionForExistent() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams/1/players?firstName=First name&lastName=Last name');
        $this->assertEquals(406, $client->getResponse()->getStatusCode());
    }

    public function existsPlayerNameActionForNoName() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams?');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}
