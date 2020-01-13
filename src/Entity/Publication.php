<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;

    /**
     * @var integer $message
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Message")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $messages;

    /**
     * @var integer $user
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Kill")
     * @ORM\JoinColumn(name="kill_id", referencedColumnName="id")
     */
    private $kill;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getMessages(): ?Message
    {
        return $this->messages;
    }

    public function setMessages(?Message $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getKill(): ?Kill
    {
        return $this->kill;
    }

    public function setKill(?Kill $kill): self
    {
        $this->kill = $kill;

        return $this;
    }
}
