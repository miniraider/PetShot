<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Animal;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserKillRepository")
 */
class UserKill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $lng;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;

    /**
     * @var integer $animal
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Animal", inversedBy="kill")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $animal;

    /**
     * @var integer $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="kill")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
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

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
