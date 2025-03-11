<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductFormType;
use App\Manager\OrderManager;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

class ProductController extends AbstractController
{
    public function __construct(private readonly UserResolver $userResolver)
    {
    }

    #[Route('/product/{id}', name: 'app_product')]
    public function productPage(
        Product $product,
        OrderRepository $orderRepository,
        OrderManager $orderManager,
    ): Response {

        $user = $this->userResolver->getAuthenticatedUser();

        if ($order = $orderRepository->findBasketForUser($user)) {
            $orderItem = $orderManager->getOrderItemFromOrderByProduct($order, $product);
        }

        return $this->render('productPage/product.html.twig', [
            'orderItem' => $orderItem ?? null,
            'product' => $product,
        ]);
    }

    #[Route('/product/add', name: 'app_product_add')]
    public function addProduct(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
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

    #[Route('/api/products', name: 'app_product_api', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of products',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: [Product::GROUP_LIST]))
        )
    )]
    #[Security(name: 'Bearer')]
    public function productList(
        ProductRepository $productRepository,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache,
    ): JsonResponse {

        try {
            $products = $cache->get(Product::CACHE_TAG_LIST, function (ItemInterface $item) use ($productRepository) {
                $item->tag(Product::CACHE_TAG_LIST);
                $item->expiresAfter(86400); // 24 hours cache time

                return $productRepository->findAll();
            });
        } catch (InvalidArgumentException) {
            return new JsonResponse('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ([] === $products) {
            throw $this->createNotFoundException('There are no products yet on the site, please check back later');
        }

        $serializedProducts = $serializer->serialize(data: $products, format:'json', context: ['groups' => Product::GROUP_LIST]);

        return new JsonResponse($serializedProducts, Response::HTTP_OK, [], true);
    }
}
