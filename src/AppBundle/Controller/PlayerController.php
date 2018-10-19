<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
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

}
