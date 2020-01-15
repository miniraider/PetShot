<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
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
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="integer")
     */
    private $aggressivity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trend;

    /**
     * @ORM\Column(type="string")
     */
    private $rarety;

    /**
     * @ORM\Column(type="integer")
     */
    private $remaining;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scientificName;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $marine;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $freshwater;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $terrestrial;

    /**
     * @ORM\Column(type="integer")
     */
    private $taxon;

    /**
     * @var integer $category
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $category;

    /**
     * @var integer $kill
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserKill", mappedBy="animal")
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

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTrend(): ?bool
    {
        return $this->trend;
    }

    public function setTrend(bool $trend): self
    {
        $this->trend = $trend;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAggressivity(): ?int
    {
        return $this->aggressivity;
    }

    public function setAggressivity(int $aggressivity): self
    {
        $this->aggressivity = $aggressivity;

        return $this;
    }

    public function getRemaining(): ?int
    {
        return $this->remaining;
    }

    public function setRemaining(int $remaining): self
    {
        $this->remaining = $remaining;

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
            $kill->setAnimal($this);
        }

        return $this;
    }

    public function removeKill(UserKill $kill): self
    {
        if ($this->kill->contains($kill)) {
            $this->kill->removeElement($kill);
            // set the owning side to null (unless already changed)
            if ($kill->getAnimal() === $this) {
                $kill->setAnimal(null);
            }
        }

        return $this;
    }

    public function getScientificName(): ?string
    {
        return $this->scientificName;
    }

    public function setScientificName(string $scientificName): self
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    public function getMarine(): ?bool
    {
        return $this->marine;
    }

    public function setMarine(bool $marine): self
    {
        $this->marine = $marine;

        return $this;
    }

    public function getFreshwater(): ?bool
    {
        return $this->freshwater;
    }

    public function setFreshwater(bool $freshwater): self
    {
        $this->freshwater = $freshwater;

        return $this;
    }

    public function getTerrestrial(): ?bool
    {
        return $this->terrestrial;
    }

    public function setTerrestrial(bool $terrestrial): self
    {
        $this->terrestrial = $terrestrial;

        return $this;
    }

    public function getTaxon(): ?int
    {
        return $this->taxon;
    }

    public function setTaxon(int $taxon): self
    {
        $this->taxon = $taxon;

        return $this;
    }

    public function getRarety(): ?string
    {
        return $this->rarety;
    }

    public function setRarety(string $rarety): self
    {
        $this->rarety = $rarety;

        return $this;
    }
}
