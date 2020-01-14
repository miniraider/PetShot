<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var integer $kill
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserKill", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $kill;

    public function __construct()
    {
        $this->kill = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|UserKill[]
     */
    public function getKill(): Collection
    {
        return $this->kill;
    }

    public function addKill(UserKill $kill): self
    {
        if (!$this->kill->contains($kill)) {
            $this->kill[] = $kill;
            $kill->setUser($this);
        }

        return $this;
    }

    public function removeKill(UserKill $kill): self
    {
        if ($this->kill->contains($kill)) {
            $this->kill->removeElement($kill);
            // set the owning side to null (unless already changed)
            if ($kill->getUser() === $this) {
                $kill->setUser(null);
            }
        }

        return $this;
    }
}
