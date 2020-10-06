<?php

namespace FluffyFactory\Bundle\UserStatsBundle\EventListener;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $user->setLastConnexion(new DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }
}