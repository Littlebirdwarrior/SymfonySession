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
    private ?Formation $formation = null;

    #[ORM\ManyToMany(targetEntity: Intern::class, inversedBy: 'sessions')]
    private Collection $intern;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Trainer $trainer = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class)]
    private Collection $programmes;

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

    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    public function setTrainer(?Trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    /**La méthode addProgramme prend un objet Programme en argument et l'ajoute à la collection programmes si celui-ci n'existe pas encore. 
     * Elle définit également la propriété Session de l'objet Programme sur la session actuelle. La méthode renvoie ensuite l'instance 
     * courante de l'objet Session. */

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setSession($this);
        }

        return $this;
    }

    /**La méthode removeProgramme prend un objet Programme en argument et le supprime de la collection programmes s'il existe. 
     * Si la propriété Session de l'objet Programme est définie sur la session actuelle, la propriété Session est définie sur null. La méthode renvoie ensuite l'instance courante de l'objet Session. */

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    /**!Important / A retenir
     * Les méthodes contains et removeElement sont fournies par la classe Doctrine\Common\Collections\Collection dans Symfony.
     * 
     * La méthode contains permet de vérifier si un élément est présent dans la collection. Elle prend un argument qui représente l'élément à rechercher dans la collection. La méthode retourne true si l'élément est trouvé dans la collection et false sinon.
     * 
     * La méthode removeElement permet de supprimer un élément de la collection. Elle prend un argument qui représente l'élément à supprimer de la collection. La méthode retourne true si l'élément a été trouvé et supprimé de la collection, et false sinon.
     */
  

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

}
