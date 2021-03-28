<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private UserService $userService;

    private UserProviderFactory $userProviderFactory;

    private UrlGeneratorInterface $urlGenerator;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserService $userService, UserProviderFactory $userProviderFactory, UrlGeneratorInterface $urlGenerator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userService = $userService;
        $this->userProviderFactory = $userProviderFactory;
        $this->urlGenerator = $urlGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return AdminContext::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod(Request::METHOD_POST);
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('_username', ''),
            'password' => $request->request->get('_password', ''),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userProviderFactory->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $session = $request->getSession();
        $user = $this->userProviderFactory->getRealUser($token->getUser());
        if ($user instanceof Model\UserInterface) {
            $deviceId = Encryptor::hash(date('YmdHis'));
            $user->setDeviceId($deviceId);

            $session->set(AdminContext::USER_DEVICE_ID, $deviceId);
            $this->userService->save($user);
        }

        if ($targetPath = $this->getTargetPath($session, $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(AdminContext::ADMIN_ROUTE));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(AdminContext::LOGIN_ROUTE);
    }
}
