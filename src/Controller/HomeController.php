<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
final class HomeController extends AbstractController{
    #[Route('/home', name: 'app_home')]
    public function index(
        ProductRepository $productRepository
    ): Response
    {
        $products = $productRepository->findBy([], ['id' => 'ASC']);

        return $this->render('home/homeIndex.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
        ]);
    }


    #[Route('/api/test/{name}', name: 'api_test', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: "Returns the test response",
        content: new OA\JsonContent(
            type: "string",
            example: "Hello World"
        )
    )]
    #[Security(name: 'Bearer')]
    public function apiTestRoute(string $name): Response
    {
        return new Response('Hello ' . $name);
    }
}
