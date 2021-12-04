<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Authorization;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as SymfonyVoter;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Voter extends SymfonyVoter
{
    public function __construct(private readonly PermissionService $permissionService, private readonly GroupService $groupService)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $subject instanceof MenuInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $group = $user->getGroup();
        if (!$group instanceof GroupInterface) {
            return false;
        }

        $superAdmin = $this->groupService->getSuperAdmin();
        if ($superAdmin->getCode() === $group->getCode()) {
            return true;
        }

        $permission = $this->permissionService->getPermission($group, $subject);
        if (!$permission instanceof PermissionInterface) {
            return false;
        }

        return match ($attribute) {
            Permission::ADD => $permission->isAddable(),
            Permission::EDIT => $permission->isEditable(),
            Permission::VIEW => $permission->isAddable() || $permission->isEditable() || $permission->isViewable(),
            Permission::DELETE => $permission->isDeletable(),
            default => false,
        };
    }
}
