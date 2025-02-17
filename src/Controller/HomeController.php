<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\IsTrue;

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

    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['placeholder' => 'Entrez votre email']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez votre mot de passe']
            ])
            ->add('submit', SubmitType::class, ['label' => 'Se connecter'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->addFlash('success', 'Tentative de connexion avec : ' . $data['email']);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('logPages/loginPage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/basket', name: 'app_basket')]
    public function basketPage(): Response
    {
        return $this->render('basketPage/basket.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/account', name: 'app_account')]
    public function accountPage(): Response
    {
        return $this->render('accountPage/myAccount.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
