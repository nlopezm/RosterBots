<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
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
        $request->request->set('team', $teamId);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em->persist($player);
            $em->flush();
            return new Response("", 202);
        } else {
            return $form;
        }
    }

}
