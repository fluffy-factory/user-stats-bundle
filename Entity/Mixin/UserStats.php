<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Entity\Mixin;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;

trait UserStats
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastConnexion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastVisited;

    /**
     * @ORM\Column(type="bigint")
     */
    private $nbPageViews = 0;

    /**
     * @ORM\OneToMany(targetEntity="FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userLines;

    public function __construct() {
        $this->userLines = new ArrayCollection();
    }

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

    /**
     * @return mixed
     */
    public function getLastVisited()
    {
        return $this->lastVisited;
    }

    /**
     * @param mixed $lastVisited
     */
    public function setLastVisited($lastVisited): void
    {
        $this->lastVisited = $lastVisited;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserLines(): Collection
    {
        return $this->userLines;
    }

    public function addUserLines(UserStatsLines $userLines): self
    {
        if (!$this->userLines->contains($userLines)) {
            $this->userLines[] = $userLines;
            $userLines->setUser($this);
        }

        return $this;
    }

    public function removeUserLines(UserStatsLines $userLines): self
    {
        if ($this->userLines->contains($userLines)) {
            $this->userLines->removeElement($userLines);
            // set the owning side to null (unless already changed)
            if ($userLines->getUser() === $this) {
                $userLines->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getNbPageViews(): int
    {
        return $this->nbPageViews;
    }

    /**
     * @param int $nbPageViews
     */
    public function setNbPageViews(int $nbPageViews): void
    {
        $this->nbPageViews = $nbPageViews;
    }
}
