<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Entity;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "FluffyFactory\Bundle\UserStatsBundle\Repository\UserStatsLinesRepository")]
#[ORM\Index(columns: ["created_at"], name: "date_archive")]
class UserStatsLines
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ManyToOne(targetEntity: User::class, inversedBy: "userLines")]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank]
    private $createdAt;

    #[ORM\Column(type: "text")]
    private $url;

    #[ORM\Column(type: "text")]
    private $route;

    #[ORM\Column(type: "string", nullable: true)]
    private $sessionId;

    #[ORM\Column(type: "string")]
    private $browser;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function setBrowser($browser): self
    {
        $this->browser = $browser;

        return $this;
    }
}
