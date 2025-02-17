<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: 'text', length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', length: 150, nullable: true)]
    private ?string $descPreview = null;

    #[ORM\Column(length: 255)]
    private ?string $imgSrc = null;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImgSrc(): ?string
    {
        return $this->imgSrc;
    }

    public function setImgSrc(string $imgSrc): static
    {
        $this->imgSrc = $imgSrc;

        return $this;
    }

    public function getDescPreview(): ?string
    {
        return $this->descPreview;
    }

    public function setDescPreview(?string $descPreview): void
    {
        $this->descPreview = $descPreview;
    }
}
