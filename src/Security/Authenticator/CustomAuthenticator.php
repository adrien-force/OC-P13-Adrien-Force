<?php

namespace App\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomAuthenticator extends JWTAuthenticator
{
    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $eventDispatcher, TokenExtractorInterface $tokenExtractor, UserProviderInterface $userProvider, ?TranslatorInterface $translator = null)
    {
        parent::__construct($jwtManager, $eventDispatcher, $tokenExtractor, $userProvider, $translator);
    }

    public function authenticate(Request $request): Passport
    {
        dd('authenticate called');
        return parent::authenticate($request);
    }

    public function loadUser(array $payload, string $identity): UserInterface
    {
        dd('loadUser called');
        return parent::loadUser($payload, $identity);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        dd($passport);
        $user = $passport->getUser();

        $requiredRole = 'ROLE_ADMIN';
        if (!in_array($requiredRole, $user->getRoles())) {
            throw new AuthenticationException('User does not have the required role.');
        }

        return parent::createToken($passport, $firewallName);
    }


}
