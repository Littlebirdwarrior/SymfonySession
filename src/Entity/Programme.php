<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $moduleDuration = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?session $session = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModuleDuration(): ?int
    {
        return $this->moduleDuration;
    }

    public function setModuleDuration(int $moduleDuration): self
    {
        $this->moduleDuration = $moduleDuration;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }



    public function __toString()
    {
        return $this->module . " dans la session -" . $this->session . " dure " . $this->moduleDuration . " jours ";
    }

    public function getSession(): ?session
    {
        return $this->session;
    }

    public function setSession(?session $session): self
    {
        $this->session = $session;

        return $this;
    }
}
