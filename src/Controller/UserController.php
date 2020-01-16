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
    public function getAction($id, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($id);
        if(!$user) throw new \Exception('User not found');
        return new JsonResponse($cm->getRepository('App:User')->format($user, $cm));
    }

    /**
     * @Route("/users/find/{name}", name="users_get_by_name", methods={"GET"})
     */
    public function getByNameAction($name)
    {
        $cm = $this->getDoctrine()->getManager();
        $users = $cm->getRepository('App:User')->getMatchUsers($name);
        $ret = [];
        foreach ($users as $user) {
            $ret[] = $cm->getRepository('App:User')->format($user, $cm);
        }

        return new JsonResponse($ret);
    }

    /**
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $cm = $this->getDoctrine()->getManager();
        $user = new User();
        $user
            ->setName($data['name'])
            ->setPassword($data['password'])
            ->setLastName($data['lastName'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['email'])
            ->setNationality($data['nationality'])
            ->setTitle('Baby hunter')
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
        $data = json_decode($request->getContent(), true);
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($id);
        if(!$user) throw new \Exception('User not founded');

        if(isset($data['name'])) {
            $user->setName($data['name']);
        }
        if(isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        if(isset($data['pseudo'])) {
            $user->setPseudo($data['pseudo']);
        }
        if(isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        $cm->persist($user);
        $cm->flush();
        return new JsonResponse(['state' => 'edit', 'id' => $user->getId()]);
    }

    /**
     * @Route("/users/login", name="users_login", methods={"GET"})
     */
    public function logInAction(Request $request)
    {
        //$data = json_decode($request->getContent(), true);
        $cm = $this->getDoctrine()->getManager();
        //$pseudo = $data['pseudo'];
        //$password = $data['password'];
        $pseudo = $request->query->get('pseudo');
        $password = $request->query->get('password');
        $user = $cm->getRepository('App:User')->findOneBy(['pseudo' => $pseudo, 'password' => $password]);
        if(!$user) throw new \Exception('User not found');
        return new JsonResponse($user->getId());
    }

}
