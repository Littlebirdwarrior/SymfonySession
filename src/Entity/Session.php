<?php

namespace App\Entity;

use App\Entity\Intern;
use DateTimeInterface;
use App\Entity\Formation;
use App\Entity\Programme;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Polyfill\Intl\Icu\IntlDateFormatter;
use Symfony\Component\Intl\Languages;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?int $nbPlace = null;


    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Formation $formation = null;

    #[ORM\ManyToMany(targetEntity: Intern::class, inversedBy: 'sessions')]
    private Collection $intern;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class)]
    private Collection $programmes;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Trainer $trainer = null;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }


    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Intern>
     */
    public function getIntern(): Collection
    {
        return $this->intern;
    }

    public function addIntern(Intern $intern): self
    {
        if (!$this->intern->contains($intern)) {
            $this->intern->add($intern);
        }

        return $this;
    }

    public function removeIntern(Intern $intern): self
    {
        $this->intern->removeElement($intern);

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->intern->contains($programme)) {
            $this->intern->add($programme);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        $this->intern->removeElement($programme);

        return $this;
    }

    /** Methode personnelle */
    
    public function getFrenchStartDate()
    {
        date_default_timezone_set('Europe/Paris');
        //je recupére mes objet datetime
        $start = $this->startDate;


        //je formate mes objetS
        $frenchStartDate = \IntlDateFormatter::formatObject(
            $start, IntlDateFormatter::RELATIVE_SHORT, 'fr_FR');



        return $frenchStartDate;
    
    }

    public function getFrenchEndDate()
    {
        date_default_timezone_set('Europe/Paris');
        //je recupére mes objet datetime
        $end = $this->endDate;

        //je formate mes objetS

        $frenchEndDate = \IntlDateFormatter::formatObject(
            $end, IntlDateFormatter::RELATIVE_SHORT, 'fr_FR');


        return $frenchEndDate ;

    }

    //les programmes
    public function countModulesInSession() 
    {
        $array = $this->programmes;
        
        if(!empty($array) && $array)
        {
           $countModules = count($array);
           return $countModules;
        } else 
        {
            return $countModules = 0;
        }
    }

    public function sumAllDays() 
    {
        $array = $this->programmes;

        $days = 0;

        foreach ($array as $programme)
        {
            $days += $programme->getModuleDuration();
        }

        return $days;

    }

    //Les

    public function countPlaceTaken(){
        //je recupère l'array des inscrit
        $interns = $this->intern;

        if(!empty($interns) && $interns)
        {
            $countPlaceTaken = count($interns);
            return $countPlaceTaken;
        } else 
        {
            echo "pas d'intern dans cette session";
        }

    }

    public function countPlaceLeft(){
        //je recupère le nombre de place et l'array des inscrits
        $nbInit = $this->nbPlace;
        $interns = $this->intern;

        if(!empty($interns) && $interns)
        {
            $countPlaceTaken = count($interns);
            //cas ou il n'y a pas de place disponible
            if($countPlaceTaken < $nbInit) 
            {
                $countPlaceLeft = $nbInit - $countPlaceTaken;
                return $countPlaceLeft;
            }
            else 
            {
                return $countPlaceLeft = 0 ;
            }
            
        } else 
        {
            echo "pas d'intern dans cette session";
        }

    }

    /** Methode to String */

    public function __toString()
    {
        return $this->title;
    }

    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    public function setTrainer(?Trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

}
