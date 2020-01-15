<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    /**
     * @Route("/leaderboard/users", name="user_leaderboard", methods={"GET"})
     */
    public function getUserLeaderboardAction(Request $request)
    {;
        $zone = $request->get("zone");
        $cm = $this->getDoctrine()->getManager();
        $leaderboard = $cm->getRepository('App:User')->getLeaderboard($zone);
        $ret = [];
        foreach ($leaderboard as $user) {
            $line =  $cm->getRepository('App:User')->format($user, $cm);
            $line['countKills'] = count($line['kills']);
            $ret[] = $line;
        }

        usort($ret, function($a, $b) {
            return $a['countKills'] < $b['countKills'];
        });
        return new JsonResponse($ret);
    }

    /**
     * @Route("/leaderboard/animals", name="animal_leaderboard", methods={"GET"})
     */
    public function getAnimalLeaderboardAction(Request $request)
    {
        $category = $request->get("category");
        $cm = $this->getDoctrine()->getManager();
        $leaderboard = $cm->getRepository('App:Animal')->getLeaderboard($category);
        $ret = [];
        foreach ($leaderboard as $animal) {
            $line =  $cm->getRepository('App:Animal')->formatForLeaderboard($animal, $cm);
            $line['countKills'] = count($line['kills']);
            $ret[] = $line;
        }

        usort($ret, function($a, $b) {
            return $a['countKills'] < $b['countKills'];
        });
        return new JsonResponse($ret);
    }
}
