<?php

namespace FluffyFactory\Bundle\UserStatsBundle\EventSubscriber;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class UserStatsSubscriber implements EventSubscriberInterface
{
    private $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'userStats',
            RequestEvent::class => 'onKernelRequest'
        ];
    }

    /**
     * Call all function for user stats
     */
    public function userStats()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $this->lastUserVisit($user);
        $this->nbUserPageViews($user);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * For each page see, set last visited for user
     * @param User|null $user
     */
    public function lastUserVisit(?User $user)
    {
        if ($user) {
            $user->setLastVisited(new DateTime());
        }
    }

    /**
     * Increment nb user page views
     * @param User|null $user
     */
    public function nbUserPageViews(?User $user)
    {
        if ($user) {
            $user->setNbPageViews($user->getNbPageViews() + 1);
        }
    }

    public function onKernelRequest(RequestEvent $event)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user) {
            $userStatsLines = new UserStatsLines();
            $userStatsLines->setUser($user);
            $userStatsLines->setUrl($event->getRequest()->getRequestUri());
            
            $this->em->persist($userStatsLines);
            $this->em->flush();
        }
    }
}