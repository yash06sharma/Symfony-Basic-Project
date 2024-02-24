<?php

namespace App\Entity;

use App\Repository\DatabaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DatabaseRepository::class)]
#[ORM\Table(name: '`database`')]
#[Broadcast]

class Database
{
    #[ORM\Id, ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?String $age;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 10,
        max: 10,
        minMessage: 'The number must be only {{ limit }} characters long',
        maxMessage: 'The number must be only {{ limit }} characters'
    )]
    // #[Assert\Regex(
    //     pattern: "/^([+]\d{2})?\d{10}$/",
    //     message: "Invalid mobile number format"
    // )]
    private ?string $mobile;

    #[Assert\NotNull]
    #[ORM\Column(name: 'cource', nullable:false)]  
      private ?array $cource = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $city;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $image;

    #[ORM\OneToMany(targetEntity: BasicDetail::class, mappedBy: 'databaseUserId')]
    private Collection $BasicDetailID;
    public function __construct()
    {
        $this->BasicDetailID = new ArrayCollection();
    }

    public function getId(): ?int   
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?String
    {
        return $this->age;
    }

    public function setAge(String $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getCource(): ?array
    {
        return $this->cource;
    }

    public function setCource(?array $cource): self
    {
        $this->cource = $cource;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, BasicDetail>
     */
    public function getBasicDetailID(): Collection
    {
        return $this->BasicDetailID;
    }

    public function addBasicDetailID(BasicDetail $basicDetailID): static
    {
        if (!$this->BasicDetailID->contains($basicDetailID)) {
            $this->BasicDetailID->add($basicDetailID);
            $basicDetailID->setDatabaseUserId($this);
        }

        return $this;
    }

    public function removeBasicDetailID(BasicDetail $basicDetailID): static
    {
        if ($this->BasicDetailID->removeElement($basicDetailID)) {
            // set the owning side to null (unless already changed)
            if ($basicDetailID->getDatabaseUserId() === $this) {
                $basicDetailID->setDatabaseUserId(null);
            }
        }

        return $this;
    }







}
