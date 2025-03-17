<?php

namespace App\Manager;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderItemManager
{
    public function __construct(private EntityManagerInterface $em) {}

    public function updateProductQuantity(OrderItem $orderItem, string $action, int $givenQuantity): void
    {

        match ($action) {
            'increment' => $orderItem->incrementQuantity(),
            'decrement' => $orderItem->decrementQuantity(),
            'update' => $orderItem->setQuantity($givenQuantity),
            default => null,
        };

        $this->em->persist($orderItem);
        $this->em->flush();

    }

}
