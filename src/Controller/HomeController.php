<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('login/loginPage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
