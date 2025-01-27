<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/homeIndex.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/product', name: 'app_product')]
    public function productPage(): Response
    {
        return $this->render('productPage/product.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
