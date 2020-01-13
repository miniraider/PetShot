<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/category", name="animal_list")
     */
    public function listAction()
    {
        $ret = [];
        $cm = $this->getDoctrine()->getManager();
        $animals = $cm->getRepository('App:Animal')->findAll();
        foreach ($animals as $animal) {
            $ret[] = $cm->getRepository('App:Animal')->format($animal);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/category/{id}", name="animal_get")
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $animal = $cm->getRepository('App:Animal')->findOneById($id);
        if(!$animal) throw new \Exception('Animal not found');
        return new JsonResponse($cm->getRepository('App:Animal')->format($animal));
    }
}
