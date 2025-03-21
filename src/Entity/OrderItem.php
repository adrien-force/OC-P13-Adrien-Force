<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    private ?Order $order = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    #[ORM\Column]
    private int $quantity = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        if (0 === $this->quantity) {
            $this->order?->removeorderItem($this);
        }

        return $this;
    }

    public function incrementQuantity(): void
    {
        ++$this->quantity;
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 0) {
            --$this->quantity;
        }
    }

    public function getSubTotal(): float
    {
        return $this->product?->getPrice() * $this->quantity;
    }
}
