<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function productPage(): Response
    {
        return $this->render('productPage/product.html.twig', [
            'controller_name' => 'HomeController',
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
