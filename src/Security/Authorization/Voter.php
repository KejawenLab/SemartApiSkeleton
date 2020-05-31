<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Authorization;

use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Service\PermissionService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as SymfonyVoter;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Voter extends SymfonyVoter
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
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
        if (!$user instanceof UserInterface) {
            return false;
        }

        $group = $user->getGroup();
        if (!$group instanceof GroupInterface) {
            return false;
        }

        $permission = $this->service->getPermission($group, $subject);
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
