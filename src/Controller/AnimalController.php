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

    /**
     * @Route("/animals", name="animal_import", methods={"POST"})
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
            return [
                'name' => $a->getName(),
                'category' => $a
            ];
        }, $cm->getRepository('App:Category')->findAll());

        $ret = [];
        foreach($allCategory as $category) {
            $ret[$category['name']] = $category['category'];
        }
        $allCategory = $ret;

        $allAnimals = array_map(function($a) {
            return $a->getName();
        }, $cm->getRepository('App:Animal')->findAll());

        foreach ($animals as $animal) {
            if (!in_array($animal['scientific_name'], $allAnimals)) {
                $categoryName = explode(" ", $animal["scientific_name"])[0];

                $newAnimal = new Animal();
                $newAnimal->setName(explode(" ", $animal['scientific_name'])[1])
                    ->setScore(0)
                    ->setAggressivity(1)
                    ->setRarety(1)
                    ->setTrend(false)
                    ->setRemaining(0)
                    ->setDescription("")
                    ->setCategory($allCategory[$categoryName]);

                $cm->persist($newAnimal);
                array_push($allAnimals, $animal['scientific_name']);
            }
        }

        $cm->flush();
        curl_close($ch);

        return new JsonResponse([
            "count" => count($allAnimals)
        ]);
    }
}
