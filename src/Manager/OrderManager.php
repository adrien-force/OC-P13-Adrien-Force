<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderRepository $orderRepository,
    ) {
    }

    public function createOrderFromBasket(
        Order $basket,
    ): void {
        $basket->setStatus(Order::ORDERED);
        $basket->setOrderedAt(new \DateTimeImmutable());

        $owner = $basket->getOwner();
        if (null === $owner) {
            throw new \LogicException('Basket owner must be set before creating an order.');
        }
        $owner->addOrder(new Order());

        $this->em->persist($basket);
        $this->em->flush();
    }

    public function createOrderForUser(User $user, string $status = Order::BASKET): Order
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus($status);
        $user->addOrder($order);

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

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

    public function getBasketOrCreateForUser(User $user): Order
    {
        $order = $this->orderRepository->findBasketForUser($user);

        if (!$order instanceof Order) {
            $order = $this->createOrderForUser($user, Order::BASKET);
        }

        return $order;
    }

    public function clearBasket(Order $order): void
    {
        if (Order::ORDERED === $order->getStatus()) {
            throw new \LogicException('Order is no longer a basket and should not be cleared.');
        }

        $orderItems = $order->getOrderItems();
        foreach ($orderItems as $orderProduct) {
            $this->em->remove($orderProduct);
        }
        $this->em->persist($order);
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
}
