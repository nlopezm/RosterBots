<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LeagueControllerTest extends WebTestCase {

    private $em;

    protected function setUp() {
        static::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testPostLeagueAction() {
        $client = static::createClient();
        $body = array(
            'name' => 'My test league ' . uniqid(),
            'starter_players' => 10,
            'substitute_players' => 5,
            'salary_cap' => 175,
        );
        $crawler = $client->request('POST', '/api/leagues', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $client->getResponse()->getContent();
    }

    public function testGetLeagueAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetLeaguesAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/leagues');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutLeagueAction() {
        $client = static::createClient();
        $body = array(
            'name' => 'My updated test league',
            'starter_players' => rand(1, 20),
            'substitute_players' => rand(1, 15),
            'salary_cap' => rand(150, 300),
        );
        $crawler = $client->request('PUT', '/api/leagues/1', $body, array(), array('CONTENT_TYPE' => 'application/json'));
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

    public function testDeleteLeagueAction() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/leagues/1');
        $this->em->getFilters()->disable('softdeleteable');
        $league = $this->em->find('AppBundle:League', 1);
        //Restores the logical removed league
        $league->setDeletedAt(null);
        $this->em->persist($league);
        $this->em->flush();
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
    }

}
