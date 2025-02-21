<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductFormType;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product')]
    public function productPage(
        Product $product,
        OrderRepository $orderRepository,
        UserRepository $userRepository
    ): Response
    {
        $User = $this->getUser()?->getUserIdentifier();

        if ($User !== null) {
            $userId = $userRepository->findOneBy(['email' => $User]);
            $order = $orderRepository->findBasketForUser($userId)[0];
            $orderItems = $order->getOrderItems();
            $productInOrder = $orderItems->filter(function($orderProduct) use ($product) {
                return $orderProduct->getProduct() === $product;
            })->first();
        }

        return $this->render('productPage/product.html.twig', [
            'controller_name' => 'HomeController',
            'productInOrder' => $productInOrder ?? null,
            'product' => $product,
        ]);
    }

    #[Route('/product/add', name: 'app_product_add')]
    public function addProduct(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $product = new Product();
        $form = $this->createForm(AddProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product added successfully');
            return $this->redirectToRoute('app_product');
        }

        return $this->render('productPage/addProduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
