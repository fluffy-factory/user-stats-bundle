<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Entity\Mixin;

use Doctrine\ORM\Mapping as ORM;

trait UserStats
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastConnexion;

    /**
     * @return mixed
     */
    public function getLastConnexion()
    {
        return $this->lastConnexion;
    }

    /**
     * @param mixed $lastConnexion
     */
    public function setLastConnexion($lastConnexion): void
    {
        $this->lastConnexion = $lastConnexion;
    }
}
