<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: "integer")]
    private $id;

    
    #[ORM\Column(name: "nom", type: "string", length: 50)]
    #[Assert\NotBlank(message: "First name must be non-empty")]
    #[Assert\Length(max: 50, maxMessage: "First name cannot exceed {{ limit }} characters")]
    private $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 50)]
    private $prenom ;

    #[ORM\Column(name: "password", type: "string", length: 50)]
    private $password ;

    #[ORM\Column(name: "Balance", type: "float", length: 50)]

    private $balance ;

    #[ORM\Column(name: "adresse", type: "string", length: 50)]
    private $adresse ;

    #[ORM\Column(name: "passeport", type: "integer", length: 50)]
    private $passeport ;

    #[ORM\Column(name: "email", type: "string", length: 50)]
    private $email ;

    #[ORM\Column(name: "type", type: "string", length: 50)]
    private  $type ;

   


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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

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

    public function getPasseport(): ?int
    {
        return $this->passeport;
    }

    public function setPasseport(int $passeport): self
    {
        $this->passeport = $passeport;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    //Source externe
    public function eraseCredentials()
    {
        // Réinitialiser les données sensibles de l'utilisateur
        // Cette méthode n'est généralement pas utilisée dans une application réelle
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        // guarantee every user at least has ROLE_USER
        

        return array_unique($roles);    }

    public function getSalt()
    {
        // Retourner le sel utilisé pour hasher le mot de passe
        // Cette méthode peut être laissée vide si vous utilisez un hachage de mot de passe moderne
    }

    public function getUsername()
    {
        // Retourner le nom d'utilisateur de l'utilisateur
         return $this->email;
    }
   
// public function setDateCreateCompte(\DateTimeInterface $dateCreateCompte): self
// {
//     $this->dateCreateCompte = $dateCreateCompte;

//     return $this;
// }

// public function setLastModifyData(\DateTimeInterface $lastModifyData): self
// {
//     $this->lastModifyData = $lastModifyData;

//     return $this;
// }

// public function setLastModifyPassword(\DateTimeInterface $lastModifyPassword): self
// {
//     $this->lastModifyPassword = $lastModifyPassword;

//     return $this;
// }

// public function setActive(bool $active): self
// {
//     $this->active = $active;

//     return $this;
// }


    
}

