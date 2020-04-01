<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @UniqueEntity("email", message="Cet email existe déjà ! Vérifiez la liste de vos clients...")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fiche-client", "chart-entreprise"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     *
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     * @Assert\Length(min="5", max="5", exactMessage="Vous devez entrer un code postal à 5 chiffres")
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche-client"})
     * @Assert\NotBlank(message="Ce champ ne peut être vide !")
     * @Assert\Length(
     *     min="10",
     *     max="10",
     *     exactMessage="Le numéro de téléphone doit être composé de 10 chiffres"
     * )
     */
    private $tel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="client")
     *
     */
    private $entreprise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facture", mappedBy="client")
     * @Groups({"fiche-client"})
     */
    private $facture;

    public function __construct()
    {
        $this->facture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

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

    /**
     * @return Collection|Facture[]
     */
    public function getFacture(): Collection
    {
        return $this->facture;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->facture->contains($facture)) {
            $this->facture[] = $facture;
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->facture->contains($facture)) {
            $this->facture->removeElement($facture);
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }
}
