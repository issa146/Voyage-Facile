<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $destination = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;


    #[ORM\Column(length: 50, nullable: true)]
    private ?string $compagnie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $aeroportDepart = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $aeroportArrivee = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixVolEstime = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nomHebergement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeHebergement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $localisationHebergement = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixHebergementEstime = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formule $formule = null;

    /**
     * @var Collection<int, Activite>
     */
    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'voyage', orphanRemoval: true)]
    private Collection $activites;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $prix = null;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
    return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
    $this->dateDebut = $dateDebut;
    return $this;
    }


    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }


    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(string $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    public function getAeroportDepart(): ?string
    {
        return $this->aeroportDepart;
    }

    public function setAeroportDepart(string $aeroportDepart): static
    {
        $this->aeroportDepart = $aeroportDepart;

        return $this;
    }

    public function getAeroportArrivee(): ?string
    {
        return $this->aeroportArrivee;
    }

    public function setAeroportArrivee(string $aeroportArrivee): static
    {
        $this->aeroportArrivee = $aeroportArrivee;

        return $this;
    }

    public function getPrixVolEstime(): ?float
    {
        return $this->prixVolEstime;
    }

    public function setPrixVolEstime(float $prixVolEstime): static
    {
        $this->prixVolEstime = $prixVolEstime;

        return $this;
    }

    public function getNomHebergement(): ?string
    {
        return $this->nomHebergement;
    }

    public function setNomHebergement(string $nomHebergement): static
    {
        $this->nomHebergement = $nomHebergement;

        return $this;
    }

    public function getTypeHebergement(): ?string
    {
        return $this->typeHebergement;
    }

    public function setTypeHebergement(string $typeHebergement): static
    {
        $this->typeHebergement = $typeHebergement;

        return $this;
    }

    public function getLocalisationHebergement(): ?string
    {
        return $this->localisationHebergement;
    }

    public function setLocalisationHebergement(string $localisationHebergement): static
    {
        $this->localisationHebergement = $localisationHebergement;

        return $this;
    }

    public function getPrixHebergementEstime(): ?float
    {
        return $this->prixHebergementEstime;
    }

    public function setPrixHebergementEstime(float $prixHebergementEstime): static
    {
        $this->prixHebergementEstime = $prixHebergementEstime;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getFormule(): ?formule
    {
        return $this->formule;
    }

    public function setFormule(?formule $formule): static
    {
        $this->formule = $formule;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setVoyage($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getVoyage() === $this) {
                $activite->setVoyage(null);
            }
        }

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}
