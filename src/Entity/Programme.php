<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $moduleDuration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModuleDuration(): ?\DateTimeInterface
    {
        return $this->moduleDuration;
    }

    public function setModuleDuration(\DateTimeInterface $moduleDuration): self
    {
        $this->moduleDuration = $moduleDuration;

        return $this;
    }
}
