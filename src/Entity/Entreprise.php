<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 * @UniqueEntity("email", message="Cet email existe déjà ! Rendez-vous sur la page de connexion !")
 */
class Entreprise implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="6", minMessage="Le mot de passe doit faire au moins 6 caractères")
     *
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *     propertyPath="password",
     *     message="Le mot de passe saisi ne correspond pas au précédent ! Essayez à nouveau..."
     * )
     *
     */
    private $confirmPassword;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $intitule;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     * @Assert\Length(min="5", max="5", exactMessage="Vous devez entrer un code postal à 5 chiffres")
     *
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire.")
     * @Assert\Length(
     *     min="9",
     *     max="9",
     *     exactMessage="Le numéro de téléphone doit être composé de 10 chiffres"
     * )
     */
    private $tel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="entreprise")
     * @Groups({"chart-entreprise"})
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facture", mappedBy="entreprise")
     * @Groups({"chart-entreprise"})
     */
    private $facture;

    /**
     * @ORM\Column(length=255)
     * @Assert\Image(
     *     maxWidth="500",
     *     maxWidthMessage="La largeur de l'image doit faire moins de 500 pixels",
     *     maxHeight="500",
     *     maxHeightMessage="La hauteur de l'image doit faire moins de 500 pixels"
     *  )
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentionLegale1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentionLegale2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentionLegale3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcs;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->facture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * @param mixed $confirmPassword
     */
    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitlue): self
    {
        $this->intitule = $intitlue;

        return $this;
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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
            $client->setEntreprise($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->client->contains($client)) {
            $this->client->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getEntreprise() === $this) {
                $client->setEntreprise(null);
            }
        }

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
            $facture->setEntreprise($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->facture->contains($facture)) {
            $this->facture->removeElement($facture);
            // set the owning side to null (unless already changed)
            if ($facture->getEntreprise() === $this) {
                $facture->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getMentionLegale1(): ?string
    {
        return $this->mentionLegale1;
    }

    public function setMentionLegale1(?string $mentionLegale1): self
    {
        $this->mentionLegale1 = $mentionLegale1;

        return $this;
    }

    public function getMentionLegale2(): ?string
    {
        return $this->mentionLegale2;
    }

    public function setMentionLegale2(?string $mentionLegale2): self
    {
        $this->mentionLegale2 = $mentionLegale2;

        return $this;
    }

    public function getMentionLegale3(): ?string
    {
        return $this->mentionLegale3;
    }

    public function setMentionLegale3(?string $mentionLegale3): self
    {
        $this->mentionLegale3 = $mentionLegale3;

        return $this;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(?string $rcs): self
    {
        $this->rcs = $rcs;

        return $this;
    }
}
