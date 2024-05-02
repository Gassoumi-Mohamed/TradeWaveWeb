<?php

namespace App\Entity;

use App\Repository\PubliciteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PubliciteRepository::class)]
class Publicite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a type')]
    #[Assert\Length(max: 255, maxMessage: 'The type cannot be longer than {{ limit }} characters')]
    private ?string $typePub = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type('\DateTimeInterface', message: 'The value {{ value }} is not a valid date.')]
    private ?\DateTimeInterface $datePub = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please enter a duration')]
    #[Assert\Type('integer', message: 'The value {{ value }} is not a valid integer.')]
    #[Assert\Positive(message: 'The duration must be a positive number.')]
    private ?int $duration = null;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'publicite')]
    private Collection $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypePub(): ?string
    {
        return $this->typePub;
    }

    public function setTypePub(string $typePub): static
    {
        $this->typePub = $typePub;

        return $this;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->datePub;
    }

    public function setDatePub(\DateTimeInterface $datePub): static
    {
        $this->datePub = $datePub;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setPublicite($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getPublicite() === $this) {
                $medium->setPublicite(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->typePub;
    }
}
