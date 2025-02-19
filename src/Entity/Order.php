<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, BasketProduct>
     */
    #[ORM\ManyToMany(targetEntity: BasketProduct::class)]
    private Collection $basketProducts;

    #[ORM\Column]
    private ?\DateTimeImmutable $orderedAt = null;

    public function __construct()
    {
        $this->basketProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, BasketProduct>
     */
    public function getProducts(): Collection
    {
        return $this->basketProducts;
    }

    public function addBasketProduct(BasketProduct $basketProduct): static
    {
        if (!$this->basketProducts->contains($basketProduct)) {
            $this->basketProducts->add($basketProduct);
        }

        return $this;
    }

    public function removeBasketProduct(BasketProduct $basketProduct): static
    {
        $this->basketProducts->removeElement($basketProduct);

        return $this;
    }

    public function getOrderedAt(): ?\DateTimeImmutable
    {
        return $this->orderedAt;
    }

    public function setOrderedAt(\DateTimeImmutable $orderedAt): static
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->basketProducts as $basketProduct) {
            $total += $basketProduct->getProduct()->getPrice() * $basketProduct->getQuantity();
        }

        return $total;
    }
}
