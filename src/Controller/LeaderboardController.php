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
        /*
        $ret = [];
        $cm = $this->getDoctrine()->getManager();
        $kills = $cm->getRepository('App:UserKill')->findAll();
        foreach ($kills as $kill) {
            $ret[] = $cm->getRepository('App:UserKill')->format($kill, $cm);
        }
        */
        return new JsonResponse($ret);
    }
}
