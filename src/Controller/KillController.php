<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\UserKill;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class KillController extends AbstractController
{
    /**
     * @Route("/kills", name="kills_list", methods={"GET"})
     */
    public function listAction()
    {;
        $ret = [];
        $cm = $this->getDoctrine()->getManager();
        $kills = $cm->getRepository('App:UserKill')->findAll();
        foreach ($kills as $kill) {
            $ret[] = $cm->getRepository('App:UserKill')->format($kill, $cm);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/kills/{id}", name="kills_get", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $kill = $cm->getRepository('App:UserKill')->findOneById($id);
        if(!$kill) throw new \Exception('Kill not found');
        return new JsonResponse($cm->getRepository('App:UserKill')->format($kill, $cm));
    }

    /**
     * @Route("/kills/user/{user}", name="kills_get_by_name", methods={"GET"}, requirements={"user": "\d"})
     */
    public function getByUserAction($user)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findById($user);
        if(!$user) throw new \Exception('User not founded');
        $ret = [];
        $kills = $cm->getRepository('App:UserKill')->findByUser($user);
        foreach ($kills as $kill) {
            $ret[] = $cm->getRepository('App:UserKill')->format($kill, $cm);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/kills", name="kills_add", methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($request->request->get('user'));
        $animal = $cm->getRepository('App:Animal')->findOneById($request->request->get('animal'));
        if(!$animal) throw new \Exception('Animal not found');
        if(!$user) throw new \Exception('User not found');
        $kill = new UserKill();
        $kill
            ->setAnimal($animal)
            ->setUser($user)
            ->setDateAdd(new \DateTime())
            ->setLat($request->request->get('lat') ? $request->request->get('lat') : 0)
            ->setLng($request->request->get('lng') ? $request->request->get('lng') : 0)
            ->setScore(2560)
        ;

        $cm->persist($kill);

        $newPost = new Publication();
        $newPost->setUser($user)
            ->setKill($kill)
            ->setDateAdd(new \DateTime());

        $cm->persist($newPost);
        $cm->flush();
        return new JsonResponse(['state' => 'added', 'id' => $kill->getId()]);
    }
}
