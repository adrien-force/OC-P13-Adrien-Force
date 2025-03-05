<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class CustomAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() !== null &&
            str_starts_with($request->getPathInfo(), '/api');
    }

    public function authenticate(Request $request): Passport
    {
        $content = $this->validateRequest($request);

        $email = $content['username'] ?? '';
        $password = $content['password'] ?? '';

        return new Passport(
            new UserBadge($email, fn($userIdentifier) => $this->loadUserByIdentifier($userIdentifier, $password)),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return match (true) {
            $exception instanceof UserNotFoundException,
                $exception instanceof BadCredentialsException
            => new JsonResponse(['message' => 'Identifiants incorrects'], Response::HTTP_UNAUTHORIZED),
            $exception instanceof CustomUserMessageAuthenticationException
            => new JsonResponse(['message' => 'Accès API non activé'], Response::HTTP_FORBIDDEN),
            $exception instanceof AuthenticationException
            => new JsonResponse(['message' => 'Veuillez fournir username et password'], Response::HTTP_BAD_REQUEST),
            default
            => new JsonResponse(['message' => 'Erreur d\'authentification'], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }

    public function validateRequest(Request $request): array
    {
        try {
            $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new AuthenticationException('Invalid JSON format');
        }

        if (empty($content) || !isset($content['username']) || !isset($content['password'])) {
            throw new AuthenticationException('No credentials provided');
        }

        return $content;
    }

    private function loadUserByIdentifier(string $userIdentifier, string $password): User
    {
        try {
            $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);

            if (!$user) {
                throw new UserNotFoundException();
            }

            if (!$this->userPasswordHasher->isPasswordValid($user, $password)) {
                throw new BadCredentialsException('Invalid password');
            }

            if (!in_array(User::API_ACCESS, $user->getRoles())) {
                throw new CustomUserMessageAuthenticationException('API access not allowed');
            }

            return $user;
        } catch (UserNotFoundException|BadCredentialsException|CustomUserMessageAuthenticationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw new AuthenticationException('An error occurred during authentication', 0, $exception);
        }
    }
}
