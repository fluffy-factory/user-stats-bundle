<?php

namespace FluffyFactory\Bundle\UserStatsBundle\EventSubscriber;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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
            RequestEvent::class => 'onKernelRequest'
        ];
    }

    /**
     * Create user stats line for each page views
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user) {
            $user->setLastVisited(new DateTime());
            $user->setNbPageViews($user->getNbPageViews() + 1);

            $userStatsLines = new UserStatsLines();
            $userStatsLines->setUser($user);
            $userStatsLines->setUrl($event->getRequest()->getRequestUri());
            $userStatsLines->setRoute($event->getRequest()->get('_route'));
            $userStatsLines->setBrowser($event->getRequest()->server->get('HTTP_USER_AGENT'));

            $this->em->persist($user);
            $this->em->persist($userStatsLines);
            $this->em->flush();
        }
    }
}