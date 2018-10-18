<?php

namespace AppBundle\Controller;

use AppBundle\Entity\League;
use AppBundle\Form\LeagueType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Head;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class LeagueController extends FOSRestController {

    /**
     * @Get("")
     */
    public function getLeaguesAction() {
        return $this->getDoctrine()->getRepository("AppBundle:League")->findAll();
    }

    /**
     * @Get("/{id}")
     */
    public function getLeagueAction($id) {
        if ($league = $this->getDoctrine()->getRepository("AppBundle:League")->find($id))
            return $league;
        return new Response("", 404);
    }

    /**
     * @Post("")
     */
    public function postLeagueAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $league = new League();
        $form = $this->createForm(LeagueType::class, $league);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em->persist($league);
            $em->flush();
            return $league->getId();
        } else {
            return $form;
        }
    }

    /**
     * @Delete("/{id}")
     */
    public function deleteLeagueAction($id) {
        if (!$league = $this->getDoctrine()->getRepository("AppBundle:League")->find($id))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $em->remove($league);
        $em->flush();
        return new Response("", 202);
    }

    /**
     * @Put("/{id}")
     */
    public function updateLeagueAction(Request $request, $id) {
        if (!$league = $this->getDoctrine()->getRepository("AppBundle:League")->find($id))
            return new Response("", 404);
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(LeagueType::class, $league);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em->persist($league);
            $em->flush();
            return new Response("", 202);
        } else {
            return $form;
        }
    }

    /**
     * @QueryParam(name="name")
     * @Head("")
     */
    public function existsLeagueNameAction(ParamFetcherInterface $paramFetcher) {
        $name = $paramFetcher->get('name');
        if (!($name && sizeof($name)))
            return new Response("", 400);
        if (!$this->getDoctrine()->getRepository("AppBundle:League")->findOneByName($name))
            return new Response("", 200);
        return new Response("", 406);
    }

}
