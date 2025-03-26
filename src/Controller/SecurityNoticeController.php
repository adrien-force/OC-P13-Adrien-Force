<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityNoticeController extends AbstractController
{
    #[Route('/security-notice', name: 'app_security_notice')]
    public function securityNotice(Request $request): Response
    {
        return $this->render(
            'securityNotice/securityNotice.html.twig',
            [
                'message' => $request->query->get('message') ?? "Vous avez été déconnecté pour des raisons de sécurité. \n Veuillez vous reconnecter.",
            ],
        );
    }

}
