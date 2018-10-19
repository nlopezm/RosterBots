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
        $body = array('firstName' => 'First name' . uniqid(), 'lastName' => 'Last name');
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

    public function testPutPlayerExceedScore() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 200, 'strength' => 4, 'agility' => 6, 'salary' => 4);
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPutPlayerExceedSalary() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 10, 'strength' => 4, 'agility' => 6, 'salary' => 999999);
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testPutPlayerNotUniqueScore() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 5, 'strength' => 0, 'agility' => 0, 'salary' => 10);
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/2', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testPutPlayerNotUniqueName() {
        $client = static::createClient();
        $body = array('firstName' => 'Sarah', 'lastName' => 'Davies',
            'unique_id' => substr(uniqid(), -6), 'speed' => 5, 'strength' => 0, 'agility' => 0, 'salary' => 10);
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1/players/2', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testDeletePlayerAction() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/leagues/1/teams/1/players/1');
        $this->em->getFilters()->disable('softdeleteable');
        $player = $this->em->find('AppBundle:Player', 1);
        //Restores the logical removed player
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

    public function testPostPlayer() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 2, 'strength' => 4, 'agility' => 6, 'salary' => 4);
        $crawler = $client->request('POST', '/api/leagues/1/teams/1/players/' . rand(0, 1), $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $player = $this->em->find('AppBundle:Player', $client->getResponse()->getContent());
        $this->em->getFilters()->disable('softdeleteable');
        $this->em->remove($player);
        $this->em->flush();
    }

    public function testPostPlayerExceedScore() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 200, 'strength' => 4, 'agility' => 6, 'salary' => 4);
        $crawler = $client->request('POST', '/api/leagues/1/teams/1/players/' . rand(0, 1), $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPostPlayerExceedSalary() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 10, 'strength' => 4, 'agility' => 6, 'salary' => 999999);
        $crawler = $client->request('POST', '/api/leagues/1/teams/1/players/' . rand(0, 1), $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testPostPlayerNotUniqueScore() {
        $client = static::createClient();
        $body = array('firstName' => 'First Name' + uniqid(), 'lastName' => 'Last name',
            'unique_id' => substr(uniqid(), -6), 'speed' => 5, 'strength' => 0, 'agility' => 0, 'salary' => 10);
        $crawler = $client->request('POST', '/api/leagues/1/teams/1/players/' . rand(0, 1), $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testPostPlayerNotUniqueName() {
        $client = static::createClient();
        $body = array('firstName' => 'Sarah', 'lastName' => 'Davies',
            'unique_id' => substr(uniqid(), -6), 'speed' => 5, 'strength' => 0, 'agility' => 0, 'salary' => 10);
        $crawler = $client->request('POST', '/api/leagues/1/teams/1/players/' . rand(0, 1), $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

}
