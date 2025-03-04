<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Outfit>
     */
    #[ORM\ManyToMany(targetEntity: Outfit::class, inversedBy: 'items')]
    private Collection $OutfitId;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fit = null;

    #[ORM\Column(length: 255)]
    private ?string $material = null;

    public function __construct()
    {
        $this->OutfitId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Outfit>
     */
    public function getOutfitId(): Collection
    {
        return $this->OutfitId;
    }

    public function addOutfitId(Outfit $outfitId): static
    {
        if (!$this->OutfitId->contains($outfitId)) {
            $this->OutfitId->add($outfitId);
        }

        return $this;
    }

    public function removeOutfitId(Outfit $outfitId): static
    {
        $this->OutfitId->removeElement($outfitId);

        return $this;
    }

    public function getFit(): ?string
    {
        return $this->fit;
    }

    public function setFit(?string $fit): static
    {
        $this->fit = $fit;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): static
    {
        $this->material = $material;

        return $this;
    }
}
