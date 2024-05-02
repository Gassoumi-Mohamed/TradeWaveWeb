<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idT = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateT = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?int $idu = null;

    #[ORM\Column]
    private ?int $IDmarket = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdT(): ?int
    {
        return $this->idT;
    }

    public function setIdT(int $idT): static
    {
        $this->idT = $idT;

        return $this;
    }

    public function getDateT(): ?\DateTimeInterface
    {
        return $this->dateT;
    }

    public function setDateT(\DateTimeInterface $dateT): static
    {
        $this->dateT = $dateT;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIdu(): ?int
    {
        return $this->idu;
    }

    public function setIdu(int $idu): static
    {
        $this->idu = $idu;

        return $this;
    }

    public function getIDmarket(): ?int
    {
        return $this->IDmarket;
    }

    public function setIDmarket(int $IDmarket): static
    {
        $this->IDmarket = $IDmarket;

        return $this;
    }
}
