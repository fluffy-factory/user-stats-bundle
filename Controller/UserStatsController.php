<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Controller;

use App\Entity\User;
use DateTime;
use FluffyFactory\Bundle\UserStatsBundle\Service\UserStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserStatsController extends AbstractController
{
    /**
     * @Route("/user-stats/{id}", name="fluffy_user_stats")
     * @param User $user
     * @param UserStatsService $userStatsService
     * @return Response
     */
    public function userStats(User $user, UserStatsService $userStatsService): Response
    {
        $lastConnexion = $user->getLastConnexion();
        $lastVisited = $user->getLastVisited();
        $nbPageViews = $user->getNbPageViews();
        $pageViewsToday = $userStatsService->getPageViewsPerPeriod($user, (new DateTime())->modify('midnight'), (new DateTime())->modify('23:59:59'));
        $avgUtilisation = $userStatsService->getAvgUtilisation($user);

        return $this->render('@UserStats/user-stats.html.twig', [
            'last_connexion' => $lastConnexion,
            'last_visited' => $lastVisited,
            'nb_page_views' => $nbPageViews,
            'page_views_today' => $pageViewsToday,
            'avg_utilisation' => $avgUtilisation
        ]);
    }
}