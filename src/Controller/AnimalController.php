<?php

namespace App\Controller;

use App\Entity\Animal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animals", name="animal_list", methods={"GET"})
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
     * @Route("/animals/{id}", name="animal_get", methods={"GET"})
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $animal = $cm->getRepository('App:Animal')->findOneById($id);
        if(!$animal) throw new \Exception('Animal not found');
        return new JsonResponse($cm->getRepository('App:Animal')->format($animal));
    }
}
