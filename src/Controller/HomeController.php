<?php

namespace App\Controller;

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

        return $this->render('logPages/loginPage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/signup', name: 'app_signUp')]
    public function signUp(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['placeholder' => 'Entrez votre email']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe']
            ])
            ->add('terms', CheckboxType::class, [
                'label' => 'J\'accepte les CGU de GreenGoodies',
                'mapped' => false,
                'constraints' => new IsTrue()
            ])

            ->add('submit', SubmitType::class, ['label' => 'S\'inscrire'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->addFlash('success', 'Tentative d\'inscription avec : ' . $data['email']);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('logPages/signUpPage.html.twig', [
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
