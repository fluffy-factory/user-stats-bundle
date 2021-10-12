<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Service;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;

class UserStatsService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param DateTime $begin
     * @param DateTime $end
     * @return array
     */
    public function getPageViewsPerPeriod(User $user, DateTime $begin, DateTime $end): array
    {
        return $this->em->getRepository(UserStatsLines::class)->findByPeriod($user, $begin, $end);
    }

    /**
     * @param User $user
     * @return array[]
     */
    public function getAvgUtilisation(User $user): array
    {
        $userStatsLines = $this->em->getRepository(UserStatsLines::class)->findByUser($user);

        $hoursUtilisation = [];
        $dayUtilisation = [];

        /** @var UserStatsLines $allUserStatsLine */
        foreach ($userStatsLines as $allUserStatsLine) {
            if (!isset($dayUtilisation[$allUserStatsLine->getCreatedAt()->format('H')])) {
                $dayUtilisation[$allUserStatsLine->getCreatedAt()->format('l')] = 1;
            } else {
                $dayUtilisation[$allUserStatsLine->getCreatedAt()->format('l')]++;
            }

            if (!isset($hoursUtilisation[$allUserStatsLine->getCreatedAt()->format('H')])) {
                $hoursUtilisation[$allUserStatsLine->getCreatedAt()->format('H')] = 1;
            } else {
                $hoursUtilisation[$allUserStatsLine->getCreatedAt()->format('H')]++;
            }
        }

        arsort($hoursUtilisation);
        arsort($dayUtilisation);

        return [
            'hours' => $hoursUtilisation,
            'day' => $dayUtilisation
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function getMostRouteViewed(User $user): array
    {
        $mostRouteViewed = [];
        $userStatsLines = $this->em->getRepository(UserStatsLines::class)->findByUser($user);

        /** @var UserStatsLines $userStatsLine */
        foreach ($userStatsLines as $userStatsLine) {
            if (!isset($mostRouteViewed[$userStatsLine->getRoute()])) {
                $mostRouteViewed[$userStatsLine->getRoute()] = 1;
            } else {
                $mostRouteViewed[$userStatsLine->getRoute()]++;
            }
        }

        arsort($mostRouteViewed);

        return $mostRouteViewed;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getBySession(User $user): array
    {
        $sessions = [];
        $userStatsLines = $this->em->getRepository(UserStatsLines::class)->findBySession($user);

        /** @var UserStatsLines $userStatsLine */
        foreach ($userStatsLines as $userStatsLine) {
            $sessions[$userStatsLine->getSessionId()][] = $userStatsLine;
        }

        foreach ($sessions as $session) {
            foreach ($session as $index => $userStatLine) {
                if ($index === array_key_first($session)) {
                    $session[$index]->diff = date_diff($session[$index]->getCreatedAt(), new DateTime());
                } else {
                    $session[$index]->diff = date_diff($session[$index]->getCreatedAt(), $session[$index - 1]->getCreatedAt());
                }

            }
        }

        return $sessions;
    }

    /**
     * @param User $user
     */
    public function removeUserStatsLines(User $user)
    {
        $userStatsLines = $this->em->getRepository(UserStatsLines::class)->findByUser($user);

        /** @var UserStatsLines $userStatsLine */
        foreach ($userStatsLines as $userStatsLine) {
            $this->em->remove($userStatsLine);
        }
    }
}