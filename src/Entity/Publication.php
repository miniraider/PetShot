<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity="PublicationMessage", mappedBy="publication")
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
     * @ORM\OneToOne(targetEntity="App\Entity\UserKill")
     * @ORM\JoinColumn(name="kill_id", referencedColumnName="id")
     */
    private $kill;

    /**
     * @var integer $likes
     *
     * @ORM\OneToMany(targetEntity="PublicationLike", mappedBy="publication")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $likes;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }



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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getKill(): ?UserKill
    {
        return $this->kill;
    }

    public function setKill(?UserKill $kill): self
    {
        $this->kill = $kill;

        return $this;
    }

    public function addMessage(PublicationMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setPublication($this);
        }

        return $this;
    }

    public function removeMessage(PublicationMessage $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getPublication() === $this) {
                $message->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PublicationMessage[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @return Collection|PublicationLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PublicationLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(PublicationLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }
}
