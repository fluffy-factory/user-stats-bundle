<?php

namespace FluffyFactory\Bundle\UserStatsBundle\EventSubscriber;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLinesArchives;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Exception;

class UserStatsSubscriber implements EventSubscriberInterface
{
    private $security;
    private $em;
    private $containerBag;

    public function __construct(Security $security, EntityManagerInterface $em, ContainerBagInterface $containerBag)
    {
        $this->security = $security;
        $this->em = $em;
        $this->containerBag = $containerBag;
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
        if ($this->containerBag->get('fluffy_user_stats')['user_stat_enabled']) {

            /** @var User $user */
            $user = $this->security->getUser();
            $route = $event->getRequest()->get('_route');

            if ($user && $route && !$this->security->isGranted('IS_IMPERSONATOR')) {

                $excludeRoute = $this->containerBag->get('fluffy_user_stats')['exclude_route'];

                if ($excludeRoute && in_array($route, $excludeRoute)) {
                    return;
                }

                try {
                    $user->setLastVisited(new DateTime());
                    $user->setNbPageViews($user->getNbPageViews() + 1);

                    $userStatsLines = new UserStatsLines();
                    $userStatsLines->setUser($user);
                    $userStatsLines->setUrl($event->getRequest()->getRequestUri());
                    $userStatsLines->setRoute($event->getRequest()->get('_route'));
                    $userStatsLines->setSessionId(session_id());
                    $userStatsLines->setBrowser($event->getRequest()->server->get('HTTP_USER_AGENT'));

                    $this->em->persist($user);
                    $this->em->persist($userStatsLines);

                    $this->em->flush();
                } catch (Exception $e) {
                }
            }
        }
    }
}
