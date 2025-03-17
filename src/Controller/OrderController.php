<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Service\Basket\BasketService;
use App\Service\Order\OrderService;
use App\Service\OrderItem\OrderItemService;
use App\Service\UserResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    public function __construct(
        private readonly UserResolver $userResolver,
        private readonly OrderService $orderService,
        private readonly OrderItemService $orderItemService,
        private readonly BasketService $basketService,
    ) {}

    #[Route('/order/add/{id}', name: 'app_order_add')]
    public function addProductToOrder(
        Product $product,
        OrderRepository $orderRepository,
    ): Response {

        $user = $this->userResolver->getAuthenticatedUser();
        $order = $orderRepository->findBasketForUser($user);

        if (!$order instanceof Order) {
            $order = $this->orderService->createOrderForUser($user);
        }

        $this->orderItemService->addProductToOrder($product, $order);

        return $this->redirectToRoute('app_product', ['id' => $product->getId()]);
    }

    #[Route('/basket', name: 'app_basket')]
    public function basketPage(
    ): Response {

        $user = $this->userResolver->getAuthenticatedUser();
        $order = $this->basketService->getBasketOrCreateForUser($user);
        $orderItems = $order->getOrderItems();

        if ($orderItems->isEmpty()) {
            $orderItems = null;
        }

        return $this->render('basketPage/basket.html.twig', [
            'orderItems' => $orderItems,
        ]);
    }

    #[Route('/order/clear/{id}', name: 'app_order_clear')]
    public function clearOrder(Order $order): Response
    {
        $this->basketService->clearBasket($order);

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/order/validate/{id}', name: 'app_order_validate')]
    public function validateOrder(
        Order $order,
    ): RedirectResponse {

        $this->orderService->createOrderFromBasket($order);

        return $this->redirectToRoute('app_account');
    }

    #[Route('/update-quantity/{id}', name: 'update_quantity', methods: ['POST'])]
    public function updateQuantity(
        Request $request,
        int $id,
        OrderItemRepository $orderItemRepository,
    ): RedirectResponse {

        $action = (string) $request->request->get('action');
        $quantity = (int) $request->request->get('quantity');
        $orderItem = $orderItemRepository->findOneById($id);

        if (!$orderItem instanceof OrderItem) {
            throw $this->createNotFoundException(sprintf('OrderItem with id %d not found', $id));
        }

        $this->orderItemService->updateProductQuantity($orderItem, $action, $quantity);

        return $this->redirectToRoute('app_product', ['id' => $orderItem->getProduct()?->getId()]);
    }
}
