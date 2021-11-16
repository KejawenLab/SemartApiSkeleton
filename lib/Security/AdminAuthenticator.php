<?php

namespace KejawenLab\ApiSkeleton\Security;

use DateTimeImmutable;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class AdminAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UserService $userService,
        private UserProviderFactory $userProviderFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(AdminContext::LOGIN_ROUTE);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $credentials = [
            'username' => $request->request->get('_username', ''),
            'password' => $request->request->get('_password', ''),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

        return new Passport(
            new UserBadge($credentials['username'], fn (string $userIdentifier): User => $this->userProviderFactory->loadUserByUsername($userIdentifier)),
            new PasswordCredentials($credentials['password']),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $session = $request->getSession();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return $this->redirect($session, $firewallName);
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if ($user instanceof UserInterface) {
            $deviceId = Encryptor::hash(date('YmdHis'));
            $user->setDeviceId($deviceId);
            $user->setLastLogin(new DateTimeImmutable());

            $session->set(AdminContext::USER_DEVICE_ID, $deviceId);
            $this->userService->save($user);
        }

        return $this->redirect($session, $firewallName);
    }

    private function redirect(SessionInterface $session, string $firewallName): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($session, $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(AdminContext::ADMIN_ROUTE));
    }
}
