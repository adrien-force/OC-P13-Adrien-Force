<?php

namespace App\Entity;

use App\Repository\BasketProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketProductRepository::class)]
class BasketProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'basketProducts')]
    private ?Basket $basket = null;

    #[ORM\ManyToOne(inversedBy: 'basketProducts')]
    private ?Order $order = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): static
    {
        $this->basket = $basket;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubTotal(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }

    public function setOrder(?Order $order): BasketProduct
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

}
