<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users_list", methods={"GET"})
     */
    public function listAction()
    {
        $ret = [];
        $cm = $this->getDoctrine()->getManager();
        $users = $cm->getRepository('App:User')->findAll();
        foreach ($users as $user) {
            $ret[] = $cm->getRepository('App:User')->format($user, $cm);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/users/{id}", name="users_get", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function getAction($id)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($id);
        if(!$user) throw new \Exception('User not found');
        return new JsonResponse($cm->getRepository('App:User')->format($user, $cm));
    }

    /**
     * @Route("/users/{name}", name="users_get_by_name", methods={"GET"})
     */
    public function getByNameAction($name)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findByName($name);
        if(!$user) throw new \Exception('User not founded');
        else return new JsonResponse(['id' =>$user->getId()]);
    }

    /**
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = new User();
        $user
            ->setName($request->query->get('name'))
            ->setPassword($request->query->get('password'))
        ;

        $cm->persist($user);
        $cm->flush();
        return new JsonResponse(['state' => 'added', 'id' => $user->getId()]);
    }

    /**
     * @Route("/users/{id}", name="users_edit", methods={"PUT"})
     */
    public function editAction($id, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($id);
        if(!$user) throw new \Exception('User not founded');
        $user
            ->setName($request->query->get('name'))
            ->setPassword($request->query->get('password'))
        ;
        $cm->persist($user);
        $cm->flush();
        return new JsonResponse(['state' => 'edit']);
    }
}
