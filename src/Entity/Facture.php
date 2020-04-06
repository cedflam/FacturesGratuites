<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fiche-client"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"fiche-client"})
     */
    private $dateFacture;


    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"fiche-client", "chart-entreprise"})
     *
     */
    private $totalAcompte;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"fiche-client", "chart-entreprise"})
     */
    private $crd;

    /**
     * @ORM\Column(type="float")
     */
    private $montantHt;

    /**
     * @ORM\Column(type="float")
     * @Groups({"fiche-client"})
     * @Groups({"chart-entreprise"})
     */
    private $montantTtc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="facture")
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="facture")
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Devis", inversedBy="facture", cascade={"persist", "remove"})
     */
    private $devis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Acompte", mappedBy="facture")
     */
    private $acompte;

    public function __construct()
    {
        $this->acompte = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeInterface $dateFacture): self
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }


    public function getTotalAcompte(): ?float
    {
        return $this->totalAcompte;
    }

    public function setTotalAcompte(?float $totalAcompte): self
    {
        $this->totalAcompte = $totalAcompte;

        return $this;
    }

    public function getCrd(): ?float
    {
        return $this->crd;
    }

    public function setCrd(?float $crd): self
    {
        $this->crd = $crd;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(float $montantHt): self
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * @return Collection|Acompte[]
     */
    public function getAcompte(): Collection
    {
        return $this->acompte;
    }

    public function addAcompte(Acompte $acompte): self
    {
        if (!$this->acompte->contains($acompte)) {
            $this->acompte[] = $acompte;
            $acompte->setFacture($this);
        }

        return $this;
    }

    public function removeAcompte(Acompte $acompte): self
    {
        if ($this->acompte->contains($acompte)) {
            $this->acompte->removeElement($acompte);
            // set the owning side to null (unless already changed)
            if ($acompte->getFacture() === $this) {
                $acompte->setFacture(null);
            }
        }

        return $this;
    }


}
