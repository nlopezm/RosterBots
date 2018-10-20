<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Starter;
use AppBundle\Entity\Substitute;
use AppBundle\Form\PlayerType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Head;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class PlayerController extends FOSRestController {

    /**
     * @Get("")
     */
    public function getPlayersAction($teamId) {
        return $this->getDoctrine()->getRepository("AppBundle:Player")->findBy(array('team' => $teamId));
    }

    /**
     * @Get("/{id}")
     */
    public function getPlayerAction($id, $teamId) {
        if ($player = $this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(array('id' => $id, 'team' => $teamId)))
            return $player;
        return new Response("", 404);
    }

    /**
     * @Delete("/{id}")
     */
    public function deletePlayerAction($id, $teamId) {
        if (!$player = $this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(array('id' => $id, 'team' => $teamId)))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $em->remove($player);
        $em->flush();
        return new Response("", 202);
    }

    /**
     * @Put("/{id}")
     */
    public function updatePlayerAction(Request $request, $id, $teamId) {
        if (!$player = $this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(array('id' => $id, 'team' => $teamId)))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PlayerType::class, $player);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            if (($res = $this->validatePlayer($player)) instanceof Response)
                return $res;
            $em->persist($player);
            $em->flush();
            return new Response("", 202);
        } else {
            return $form;
        }
    }

    /**
     * @QueryParam(name="firstName")
     * @QueryParam(name="lastName")
     * @Head("")
     */
    public function existsFullNameAction(ParamFetcherInterface $paramFetcher, $leagueId) {
        $teams = $this->getDoctrine()->getRepository("AppBundle:Team")->findByLeague($leagueId);
        $teamsIds = array_map(function($team) {
            return $team->getId();
        }, $teams);
        $firstName = $paramFetcher->get('firstName');
        $lastName = $paramFetcher->get('lastName');
        $bool = $firstName && sizeof($firstName);
        $bool &= $lastName && sizeof($lastName);
        if (!$bool)
            return new Response("", 400);
        if (!$this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(array('firstName' => $firstName, 'lastName' => $lastName, 'team' => $teamsIds)))
            return new Response("", 200);
        return new Response("", 406);
    }

    /**
     * @Post("/{type}")
     */
    public function postPlayerAction(Request $request, $leagueId, $teamId, $type) {
        if ($type != 0 && $type != 1)
            return new Response("Invalid type", 400);
        if ($type)
            $player = new Starter();
        else
            $player = new Substitute();
        $player->setUniqueId(strtoupper(substr(uniqid(), -6)));
        $form = $this->createForm(PlayerType::class, $player);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            if (!$team = $this->getDoctrine()->getRepository("AppBundle:Team")->findOneBy(array('id' => $teamId, 'league' => $leagueId)))
                return new Response("Invalid team or league", 400);

            $player->setTeam($team);

            if (($res = $this->validatePlayer($player)) instanceof Response)
                return $res;

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
            return $player->getId();
        } else {
            return $form;
        }
    }

    private function validatePlayer($player) {
        $team = $player->getTeam();
        if ($player->getTotalAttributeScore() > 100)
            return new Response("Sum of attributes must not exceed 100", 400);
        $teamsIds = array_map(function($team) {
            return $team->getId();
        }, $team->getLeague()->getTeams()->toArray());

        // Check name uniqueness
        if (($aPlayer = $this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(array('firstName' => $player->getFirstName(), 'lastName' => $player->getLastName(), 'team' => $teamsIds))) && $aPlayer->getId() != $player->getId())
            return new Response("First and last name combination already exists for the team", 409);

        // Unique total attribute score
        $attributeScores = array_map(function($p) use ($player) {
            return $p->getId() != $player->getId() ? $p->getTotalAttributeScore() : null;
        }, $team->getPlayers()->toArray());
        if (in_array($player->getTotalAttributeScore(), $attributeScores))
            return new Response("There is other player with the same total attribute score in the team", 409);

        // Salary check sum
        $salaries = array_sum(array_map(function($p)use ($player) {
                    return $p->getId() != $player->getId() ? $p->getSalary() : null;
                }, $team->getPlayers()->toArray()));
        if (($salaries + $player->getSalary()) > $team->getLeague()->getSalaryCap())
            return new Response("Team salaries exceed the salary cap", 409);
    }

}
