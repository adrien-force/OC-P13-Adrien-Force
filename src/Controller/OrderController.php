<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Manager\OrderManager;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order/add/{id}', name: 'app_order_add')]
    public function addProductToOrder(
        Product $product,
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        EntityManagerInterface $em,
    ): Response {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }
        $userIdentifier = $user->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $userIdentifier]);

        $order = $orderRepository->findBasketForUser($user)[0];


        if (null === $order) {
            $order = new Order();
            $order->setOwner($user);
            $user->setOrder($order);
        }

        $orderProduct = $order->getOrderItems()->filter(function ($orderProduct) use ($product) {
            return $orderProduct->getProduct() === $product;
        })->first();

        if (false !== $orderProduct) {
            $orderProduct->setQuantity($orderProduct->getQuantity() + 1);
        } else {
            $orderProduct = new OrderItem();
            $orderProduct->setOrder($order);
            $orderProduct->setProduct($product);
            $orderProduct->setQuantity(1);
            $order->addOrderItem($orderProduct);
        }

        $em->persist($orderProduct);
        $em->persist($order);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_product', ['id' => $product->getId()]);
    }

    #[Route('/basket', name: 'app_basket')]
    public function basketPage(
        OrderRepository $orderRepository,
        UserRepository $userRepository,
    ): Response {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }

        $userIdentifier = $this->getUser()->getUserIdentifier();
        /** $userID User **/
        $userID = $userRepository->findOneBy(['email' => $userIdentifier]);

        $order = $orderRepository->findBasketForUser($userID)[0];

        dump($order);
        $orderItems = $order->getOrderItems();

        if ($orderItems->isEmpty()) {
            $orderItems = null;
        }

        return $this->render('basketPage/basket.html.twig', [
            'orderItems' => $orderItems,
        ]);
    }

    #[Route('/order/clear/{id}', name: 'app_order_clear')]
    public function clearOrder(
        Order $order,
        EntityManagerInterface $em,
    ): Response {
        $orderItems = $order->getOrderItems();
        foreach ($orderItems as $orderProduct) {
            $em->remove($orderProduct);
        }
        $em->flush();

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/order/validate/{id}', name: 'app_order_validate')]
    public function validateOrder(
        Order $order,
        OrderManager $orderManager,
        EntityManagerInterface $em,
    ): RedirectResponse {
        $orderManager->createOrderFromBasket($order, $em);

        return $this->redirectToRoute('app_account');
    }

    #[Route('/update-quantity/{id}', name: 'update_quantity', methods: ['POST'])]
    public function updateQuantity(
        Request $request,
        $id,
        OrderItemRepository $orderProductRepository,
        EntityManagerInterface $em,
    ): RedirectResponse {
        $action = $request->request->get('action');

        $orderProduct = $orderProductRepository->find($id);
        $productId = $orderProduct->getProduct()->getId();

        $quantity = $orderProduct->getQuantity();

        match ($action) {
            'increment' => ++$quantity,
            'decrement' => --$quantity,
            'update' => $quantity = $request->request->get('quantity'),
            default => $quantity,
        };


        $orderProduct->setQuantity($quantity);
        $em->persist($orderProduct);
        $em->flush();

        return $this->redirectToRoute('app_product', ['id' => $productId]);
    }
}
