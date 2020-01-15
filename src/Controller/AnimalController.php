<?php

namespace App\Controller;

use App\Entity\Animal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animals", name="animal_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        $ret = [];
        $cm = $this->getDoctrine()->getManager();

        // Nom (commun et scientifique, avec "LIKE"), categorie, par type d'habitat, rareté, score
        $animals = $cm->getRepository('App:Animal')->getAnimalsFiltered(
            $request->query->get('name'),
            $request->query->get('category'),
            $request->query->get('habitat'),
            $request->query->get('rarity'),
            $request->query->get('score')
        );

        
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
