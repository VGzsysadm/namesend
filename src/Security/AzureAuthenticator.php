<?php

namespace App\Security;

use App\Entity\Suser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\AzureUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AzureAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $session;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->session = new Session();
    }

    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/connect/azure/check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getAzureClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var AzureUser $azureUser */
        $azureUser = $this->getAzureClient()
            ->fetchUserFromToken($credentials);
        $email = $azureUser->getUpn();
        $usr = $this->em->getRepository('App:User')->findOneBy(['email' => $email]);
        $suser = $this->em->getRepository('App:Suser')->findOneBy(['email' => $email]);

        if (!$usr) {
            if (!$suser) {
            $user = new Suser();
            $user->setEmail($azureUser->getUpn());
            $roles[] = 'ROLE_USER';
            $user->setRoles($roles);
            $this->em->persist($user);
            $this->em->flush();
            }
            return $suser;
        }

        return $usr;
    }

    /**
     * @return \KnpU\OAuth2ClientBundle\Client\OAuth2Client
     */
    private function getAzureClient()
    {
        return $this->clientRegistry
            ->getClient('azure');
    }

    /**
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $authException The exception that started the authentication process
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    /**
     *
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {
        $message = "Authentication failure, please try again.";
        $this->session->getFlashBag()->add("danger", $message);
        #return new RedirectResponse('/');
    }

    /**
     *
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return void
     */
    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, $providerKey)
    {
        $message = "Welcome";
        $this->session->getFlashBag()->add("success", $message);
        return new RedirectResponse('/');
    }
}