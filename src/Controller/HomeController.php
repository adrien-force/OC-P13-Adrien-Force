<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
    ): Response {
        $products = $productRepository->findBy([], ['id' => 'ASC']);

        return $this->render('home/homeIndex.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
        ]);
    }

    #[Route('/', name: 'app_root')]
    public function redirectToHome(): RedirectResponse
    {
        return $this->redirectToRoute('app_home');
    }
}
