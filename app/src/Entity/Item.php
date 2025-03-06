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
    #[ORM\ManyToOne(inversedBy: 'item')]
    private ?User $userId = null;

    #[ORM\Column]
    private ?bool $isPublic = false;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'item')]
    private Collection $likes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerUrl = null;

    public function __construct()
    {
        $this->OutfitId = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function setType(string $type): self
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

    public function setMaterial(?string $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setItem($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getItem() === $this) {
                $like->setItem(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUserId() === $user) {
                return true;
            }
        }

        return false;
    }

    public function getPartnerUrl(): ?string
    {
        return $this->partnerUrl;
    }

    public function setPartnerUrl(?string $partnerUrl): static
    {
        $this->partnerUrl = $partnerUrl;

        return $this;
    }
}
