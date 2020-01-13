<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_list")
     */
    public function listAction()
    {
        $ret = [];
        $cm = $this->getDoctrine()->getManager();
        $categories = $cm->getRepository('App:Category')->findAll();
        foreach ($categories as $category) {
            $ret[] = $cm->getRepository('App:Category')->format($category);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/category/{id}", name="category_get")
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $category = $cm->getRepository('App:Category')->findOneById($id);
        if(!$category) throw new \Exception('Category not found');
        return new JsonResponse($cm->getRepository('App:Category')->format($category));
    }
}
