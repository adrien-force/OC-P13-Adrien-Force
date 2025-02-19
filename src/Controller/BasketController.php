<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Manager\OrderManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BasketController extends AbstractController{

    #[Route('/basket/add/{id}', name: 'app_basket_add')]
    public function addProductToBasket(
        Product $product,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        $userIdentifier = $user->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $userIdentifier]);

        $basket = $user->getBasket();

        if ($basket === null) {
            $basket = new Basket();
            $basket->setOwner($user);
            $user->setBasket($basket);
        }

        $basketProduct = $basket->getBasketProducts()->filter(function($basketProduct) use ($product) {
            return $basketProduct->getProduct() === $product;
        })->first();

        if ($basketProduct !== false) {
            $basketProduct->setQuantity($basketProduct->getQuantity() + 1);
        } else {
            $basketProduct = new BasketProduct();
            $basketProduct->setBasket($basket);
            $basketProduct->setProduct($product);
            $basketProduct->setQuantity(1);
            $basket->addBasketProduct($basketProduct);
        }

        $em->persist($basketProduct);
        $em->persist($basket);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/basket', name: 'app_basket')]
    public function basketPage(
        UserRepository $userRepository
    ): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $userIdentifier = $user->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $userIdentifier]);

        $basket = $user->getBasket();
        $basketProducts = $basket->getBasketProducts();

        if ($basketProducts->isEmpty()) {
            $basketProducts = null;
        }

        return $this->render('basketPage/basket.html.twig', [
            'basketProducts' => $basketProducts,
        ]);
    }

    #[Route('/basket/clear/{id}', name: 'app_basket_clear')]
    public function clearBasket(
        Basket $basket,
        EntityManagerInterface $em
    ): Response
    {
        $basketProducts = $basket->getBasketProducts();
        foreach ($basketProducts as $basketProduct) {
            $em->remove($basketProduct);
        }
        $em->remove($basket);
        $em->flush();

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/basket/validate/{id}', name: 'app_basket_validate')]
    public function validateBasket(
        Basket $basket,
        EntityManagerInterface $em,
        OrderManager $orderManager
    ): Response
    {
        $orderManager->createOrderFromBasket($basket, $em);

        return $this->redirectToRoute('app_account');
    }
}
