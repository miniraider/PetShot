<?php

namespace App\Controller;

use App\Entity\PublicationLike;
use App\Entity\PublicationMessage;
use App\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewFeedController extends AbstractController
{
    /**
     * @Route("/newfeed/message/{publication}", name="newfeed_add_message_to_publication", methods={"POST"})
     */
    public function addMessageAction($publication, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $publication = $cm->getRepository('App:Publication')->findOneById($publication);
        $message = new PublicationMessage();
        $message
            ->setPublication($publication)
            ->setUser($cm->getRepository('App:User')->findOneById($request->query->get('user')))
            ->setDateAdd(new \DateTime())
        ;

        $cm->persist($message);
        $cm->flush();
        return new JsonResponse(['state' => 'added', 'id' => $message->getPublication()->getId()]);
    }

    /**
     * @Route("/newfeed/message/edit/{message}", name="newfeed_edit_message", methods={"PUT"})
     */
    public function editMessageAction($message, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $message->setContent($request->query->get('content'));

        $cm->persist($message);
        $cm->flush();
        return new JsonResponse(['state' => 'added', 'id' => $message->getPublication()->getId()]);
    }

    /**
     * @Route("/newfeed/follow/{source}/{target}", name="newfeed_follow", methods={"POST"})
     */
    public function followAction($source, $target, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $userSource = $cm->getRepository('App:User')->findOneById($source);
        $userTarget = $cm->getRepository('App:User')->findOneById($target);
        $userTarget->addFollower($userSource);
        $cm->persist($userTarget);
        $cm->flush();
        return new JsonResponse(['state' => 'follow', 'id' => $userTarget->getId()]);
    }

    /**
     * @Route("/newfeed/unfollow/{source}/{target}", name="newfeed_follow", methods={"PUT"})
     */
    public function unfollowAction($source, $target, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $userSource = $cm->getRepository('App:User')->findOneById($source);
        $userTarget = $cm->getRepository('App:User')->findOneById($target);
        $userTarget->removeFollower($userSource);
        $cm->persist($userTarget);
        $cm->flush();
        return new JsonResponse(['state' => 'unfollow', 'id' => $userTarget->getId()]);
    }

    /**
     * @Route("/newfeed/{user}", name="newfeed", methods={"GET"}, requirements={"id": "\d"})
     */
    public function newfeedAction($user, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($user);
        $followed = $cm->getRepository('App:User')->getFollowed($user, $cm);
        $followed = array_unique($followed);
        $publications = $cm->getRepository('App:Publication')->findByUser($followed, ['dateAdd' => 'DESC']);
        $ret = [];

        foreach ($publications as $publication) {
            $ret[] =  $cm->getRepository('App:Publication')->format($publication);
        }
        return new JsonResponse($ret);
    }

    /**
     * @Route("/newfeed/like/{user}/{publication}", name="newfeed_manage_like", methods={"GET"}, requirements={"user": "\d", "publication":"\d"})
     */
    public function manageLikeAction($user, $publication, Request $request)
    {
        $cm = $this->getDoctrine()->getManager();
        $user = $cm->getRepository('App:User')->findOneById($user);
        $publication = $cm->getRepository('App:Publication')->findOneById($publication);

        $valid = false;
        $likeObj = null;
        foreach ($publication->getLikes() as $like) {
            if($like->getUser()->getId() == $user->getId()) {
                $valid = true;
                $likeObj = $like;
            }
        }

        if($valid && $likeObj) {
            $cm->remove($likeObj);
        } else {
            $like = new PublicationLike();
            $like
                ->setUser($user)
                ->setPublication($publication)
            ;
            $cm->persist($publication);
        }

        $cm->flush();
        return new JsonResponse('ok');
    }
}
