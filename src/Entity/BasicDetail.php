<?php

namespace App\Entity;

use App\Repository\BasicDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: BasicDetailRepository::class)]
#[Broadcast]
class BasicDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'BasicDetailID')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Database $databaseUserId = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    private ?string $dist = null;

    #[ORM\Column(length: 255)]
    private ?string $zip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatabaseUserId(): ?Database
    {
        return $this->databaseUserId;
    }

    public function setDatabaseUserId(?Database $databaseUserId): static
    {
        $this->databaseUserId = $databaseUserId;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getDist(): ?string
    {
        return $this->dist;
    }

    public function setDist(string $dist): static
    {
        $this->dist = $dist;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }
}
