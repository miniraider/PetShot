<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="category_list", methods={"GET"})
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
     * @Route("/categories/{id}", name="category_get")
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $category = $cm->getRepository('App:Category')->findOneById($id);
        if(!$category) throw new \Exception('Category not found');
        return new JsonResponse($cm->getRepository('App:Category')->format($category));
    }

    /**
     * @Route("/categories", name="category_import", methods={"POST"})
     */
    public function importAction() {
        $cm = $this->getDoctrine()->getManager();

        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://apiv3.iucnredlist.org/api/v3/species/category/CR?token=9bb4facb6d23f48efbf424bb05c0c1ef1cf6f468393bc745d42179ac4aca5fee");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        $animals = json_decode($output, true)['result'];

        $allCategory = array_map(function($a) {
            return $a->getName();
        }, $cm->getRepository('App:Category')->findAll());

        foreach ($animals as $animal) {
            $categoryName = explode(" ", $animal["scientific_name"])[0];

            if(!in_array($categoryName, $allCategory)) {
                array_push($allCategory, $categoryName);

                $category = new Category();
                $category->setName($categoryName);

                $cm->persist($category);
            }
        }

        $cm->flush();
        curl_close($ch);

        return new JsonResponse([
            "count" => count($allCategory)
        ]);
    }
}