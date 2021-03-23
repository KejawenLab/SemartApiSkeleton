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
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Voter extends SymfonyVoter
{
    private PermissionService $permissionService;

    private GroupService $groupService;

    public function __construct(PermissionService $permissionService, GroupService $groupService)
    {
        $this->permissionService = $permissionService;
        $this->groupService = $groupService;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if ($subject instanceof MenuInterface) {
            return true;
        }

        return false;
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

        switch ($attribute) {
            case Permission::ADD:
                return $permission->isAddable();
                break;
            case Permission::EDIT:
                return $permission->isEditable();
                break;
            case Permission::VIEW:
                return $permission->isAddable() || $permission->isEditable() || $permission->isViewable();
                break;
            case Permission::DELETE:
                return $permission->isDeletable();
                break;
        }

        return false;
    }
}
