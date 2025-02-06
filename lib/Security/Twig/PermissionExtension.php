<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Twig;

use Iterator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\Authorization;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionExtension extends AbstractExtension
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage, private readonly GroupService $groupService, private readonly Authorization $authorization)
    {
    }

    /**
     * @return Iterator<TwigFunction>
     */
    #[\Override]
    public function getFunctions(): iterable
    {
        yield new TwigFunction('is_super_admin', [$this, 'isSuperAdmin']);
        yield new TwigFunction('can_view_audit', [$this, 'canViewAudit']);
    }

    public function canViewAudit(): bool
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->authorization->authorize(new Permission('audit', ['view']));
    }

    public function isSuperAdmin(): bool
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();
        $superAdmin = $this->groupService->getSuperAdmin();
        if ($superAdmin->getCode() !== $user->getGroup()->getCode()) {
            return false;
        }

        return true;
    }
}
