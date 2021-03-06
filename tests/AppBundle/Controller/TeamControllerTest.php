<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamControllerTest extends WebTestCase {

    private $em;

    protected function setUp() {
        static::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testPostTeamAction() {
        $client = static::createClient();
        $body = array('name' => 'My test team ' . uniqid());
        $crawler = $client->request('POST', '/api/leagues/1/teams', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $client->getResponse()->getContent();
    }

    public function testGetTeamAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues/1/teams/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetTeamsAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues/1/teams');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutTeamAction() {
        $client = static::createClient();
        $body = array('name' => 'Updated test Team');
        $crawler = $client->request('PUT', '/api/leagues/1/teams/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTeamAction() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/leagues/1/teams/1');
        $this->em->getFilters()->disable('softdeleteable');
        $team = $this->em->find('AppBundle:Team', 1);
        //Restores the logical removed league
        $team->setDeletedAt(null);
        $this->em->persist($team);
        $this->em->flush();
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }
    
    public function existsTeNameActionForNonExistent() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams?name=Non-existent');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function existsTeamNameActionForExistent() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams?name=Updated test Team');
        $this->assertEquals(406, $client->getResponse()->getStatusCode());
    }

    public function existsTeamNameActionForNoName() {
        $client = static::createClient();
        $crawler = $client->request('HEAD', '/api/leagues/1/teams?');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}
