<?php

namespace App\Controller;

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
//
//    /**
//     * @Route("/kills/user/{user}", name="kills_get_by_name", methods={"GET"}, requirements={"user": "\d"})
//     */
//    public function getByUserAction($user)
//    {
//        $cm = $this->getDoctrine()->getManager();
//        $user = $cm->getRepository('App:User')->findById($user);
//        if(!$user) throw new \Exception('User not founded');
//
//        $kills = $cm->getRepository('App:Kill')->findByUser($user);
//        foreach ($kills as $kill) {
//            $ret[] = $cm->getRepository('App:Kill')->format($kill, $cm);
//        }
//
//        return new JsonResponse($ret);
//    }
//
//    /**
//     * @Route("/kills", name="kills_add", methods={"POST"})
//     */
//    public function addAction(Request $request)
//    {
//        $cm = $this->getDoctrine()->getManager();
//        $kill = new Kill();
//        $kill
//            ->setAnimal($cm->getRepository('App:Animal')->findOneById($request->query->get('animal')))
//            ->setUser($cm->getRepository('App:User')->findOneById($request->query->get('user')))
//            ->setDateAdd(new \DateTime())
//            ->setLat(0)
//            ->setLng(0)
//            ->setScore(2560)
//        ;
//
//        $cm->persist($kill);
//        $cm->flush();
//        return new JsonResponse(['state' => 'added', 'id' => $kill->getId()]);
//    }
}
