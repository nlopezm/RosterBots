<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Team;
use AppBundle\Form\TeamType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class TeamController extends FOSRestController {

    /**
     * @Get("")
     */
    public function getTeamsAction($leagueId) {
        return $this->getDoctrine()->getRepository("AppBundle:Team")->findBy(array('league' => $leagueId));
    }

    /**
     * @Get("/{id}")
     */
    public function getTeamAction($id, $leagueId) {
        if ($team = $this->getDoctrine()->getRepository("AppBundle:Team")->findOneBy(array('id' => $id, 'league' => $leagueId)))
            return $team;
        return new Response("", 404);
    }

    /**
     * @Post("")
     */
    public function postTeamAction(Request $request, $leagueId) {
        $em = $this->getDoctrine()->getManager();
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $request->request->set('league', $leagueId);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $league = $team->getLeague();
            $this->getExistingPlayersForLeague($league, $existingNames, $existingUIds);
            $players = $this->container->get('player.service')->generatePlayers($league->getStarterPlayers(), $league->getSubstitutePlayers(), $league->getSalaryCap(), $existingNames, $existingUIds);
            $team->setPlayers($players);
            foreach ($players as $player)
                $player->setTeam($team);
            $em->persist($team);
            $em->flush();
            return $team->getId();
        } else {
            return $form;
        }
    }

    /**
     * @Delete("/{id}")
     */
    public function deleteTeamAction($id, $leagueId) {
        if (!$team = $this->getDoctrine()->getRepository("AppBundle:Team")->findOneBy(array('id' => $id, 'league' => $leagueId)))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();
        return new Response("", 202);
    }

    /**
     * @Put("/{id}")
     */
    public function updateTeamAction(Request $request, $id, $leagueId) {
        if (!$team = $this->getDoctrine()->getRepository("AppBundle:Team")->findOneBy(array('id' => $id, 'league' => $leagueId)))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TeamType::class, $team);
        $request->request->set('league', $leagueId);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em->persist($team);
            $em->flush();
            return new Response("", 202);
        } else {
            return $form;
        }
    }

    private function getExistingPlayersForLeague($league, &$existingNames, &$existingUIds) {
        $teamIds = array_map(function($team) {
            return $team->getId();
        }, $league->getTeams()->toArray());
        $existingPlayers = $this->getDoctrine()->getRepository("AppBundle:Player")->findBy(array('team' => $teamIds));
        $existingNames = array();
        $existingUIds = array();
        foreach ($existingPlayers as $player) {
            $existingNames[] = $player->getFullName();
            $existingUIds[] = $player->getUniqueId();
        }
        return true;
    }

}
