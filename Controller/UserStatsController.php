<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserStatsController extends AbstractController
{
    /**
     * @Route("/user-stats/{id}", name="fluffy_user_stats")
     * @param User $user
     * @return Response
     */
    public function userStats(User $user): Response
    {
        dd($user);
    }
}