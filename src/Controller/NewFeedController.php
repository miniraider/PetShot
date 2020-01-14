<?php

namespace App\Controller;

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
        $message
            ->setContent($request->query->get('content'))
        ;

        $cm->persist($message);
        $cm->flush();
        return new JsonResponse(['state' => 'added', 'id' => $message->getPublication()->getId()]);
    }
}
