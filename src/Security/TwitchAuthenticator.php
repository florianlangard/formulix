<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TwitchAuthenticator extends OAuth2Authenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_twitch_helix_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('twitch_helix');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                /** @var TwitchUser $twitchUser */
                $twitchUser = $client->fetchUserFromToken($accessToken);
                // dd($twitchUser);
                $email = $twitchUser->getEmail();

                // 1) have they logged in with Twitch before? Easy!
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['twitchId' => $twitchUser->getId()]);

                if ($existingUser) {
                    return $existingUser;
                }

                // 2) do we have a matching user by email?
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                // dd($user);
                if ($user === null) {
                    // 3) Maybe you just want to "register" them by creating
                    // a User object
                    $newUser = new User();
                    $newUser->setTwitchId($twitchUser->getId());
                    $newUser->setEmail($twitchUser->getEmail());
                    $newUser->setPersonaname($twitchUser->getLogin());
                    // dd($newUser);
                    $this->entityManager->persist($newUser);
                    $this->entityManager->flush();

                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('home');

        return new RedirectResponse($targetUrl);
    
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}