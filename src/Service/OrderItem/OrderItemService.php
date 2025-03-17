<?php

namespace App\Service\OrderItem;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class OrderItemService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function addProductToOrder(Product $product, Order $order): void
    {
        $orderItem = $order->getOrderItemByProduct($product);
        if (!$orderItem instanceof OrderItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($product);
            $order->addOrderItem($orderItem);
        }
        $orderItem->incrementQuantity();

        $this->em->persist($orderItem);
        $this->em->persist($order);
        $this->em->persist($product);
        $this->em->flush();
    }

    public function getOrderItemFromOrderByProduct(Order $order, Product $product): ?OrderItem
    {
        $orderItems = $order->getOrderItems();
        $orderItem = $orderItems->filter(function ($orderProduct) use ($product) {
            return $orderProduct->getProduct() === $product;
        })->first();

        if (!$orderItem instanceof OrderItem) {
            return null;
        }

        return $orderItem;
    }

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
