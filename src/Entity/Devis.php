<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevisRepository")
 */
class Devis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDevis;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montantHt;

    /**
     * @ORM\Column(type="float")
     */
    private $montantTtc;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Description", mappedBy="devis",cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Facture", mappedBy="devis", cascade={"persist", "remove"})
     */
    private $facture;

    public function __construct()
    {
        $this->description = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDevis(): ?\DateTimeInterface
    {
        return $this->dateDevis;
    }

    public function setDateDevis(\DateTimeInterface $dateDevis): self
    {
        $this->dateDevis = $dateDevis;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(?float $montantHt): self
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getMontantTtc(): ?float
    {
        return $this->montantTtc;
    }

    public function setMontantTtc(float $montantTtc): self
    {
        $this->montantTtc = $montantTtc;

        return $this;
    }

    /**
     * @return Collection|Description[]
     */
    public function getDescription(): Collection
    {
        return $this->description;
    }

    public function addDescription(Description $description): self
    {
        if (!$this->description->contains($description)) {
            $this->description[] = $description;
            $description->setDevis($this);
        }

        return $this;
    }

    public function removeDescription(Description $description): self
    {
        if ($this->description->contains($description)) {
            $this->description->removeElement($description);
            // set the owning side to null (unless already changed)
            if ($description->getDevis() === $this) {
                $description->setDevis(null);
            }
        }

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        // set (or unset) the owning side of the relation if necessary
        $newDevis = null === $facture ? null : $this;
        if ($facture->getDevis() !== $newDevis) {
            $facture->setDevis($newDevis);
        }

        return $this;
    }

}
