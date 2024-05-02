<?php

namespace App\Entity;

use App\Repository\MarketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MarketRepository::class)]
class Market
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $IDmarket = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 25)]
    #[Assert\Regex(pattern: "/\A[A-Z]/", message: "The name must start with a capital letter.")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $rate = null;

    #[ORM\Column]
    private ?float $Sprice = null;

    #[ORM\Column(nullable: true)]
    private ?float $Bprice = null;

    #[ORM\Column(nullable: true)]
    private ?float $Mcap = null;

    #[ORM\Column(name: "typeM" , length: 50, nullable: true)]
    #[Assert\Choice(choices: ['coin', 'stock'])]
    private ?string $typeM = null;

    #[ORM\Column(length: 260)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 260)]
    private ?string $IMG_SRC = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $ID_user = null;

  

    public function getIDmarket(): ?int
    {
        return $this->IDmarket;
    }

    public function setIDmarket(int $IDmarket): static
    {
        $this->IDmarket = $IDmarket;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getSprice(): ?float
    {
        return $this->Sprice;
    }

    public function setSprice(float $Sprice): static
    {
        $this->Sprice = $Sprice;

        return $this;
    }

    public function getBprice(): ?float
    {
        return $this->Bprice;
    }

    public function setBprice(?float $Bprice): static
    {
        $this->Bprice = $Bprice;

        return $this;
    }

    public function getMcap(): ?float
    {
        return $this->Mcap;
    }

    public function setMcap(?float $Mcap): static
    {
        $this->Mcap = $Mcap;

        return $this;
    }

    public function getTypeM(): ?string
    {
        return $this->typeM;
    }

    public function setTypeM(?string $typeM): static
    {
        $this->typeM = $typeM;

        return $this;
    }

    public function getIMGSRC(): ?string
    {
        return $this->IMG_SRC;
    }

    public function setIMGSRC(string $IMG_SRC): static
    {
        $this->IMG_SRC = $IMG_SRC;

        return $this;
    }

    public function getIDUser(): ?int
    {
        return $this->ID_user;
    }

    public function setIDUser(?int $ID_user): static
    {
        $this->ID_user = $ID_user;

        return $this;
    }
}
