<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MenuService extends AbstractService implements ServiceInterface
{
    private TokenStorageInterface $tokenStorage;

    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        PermissionRepositoryInterface $permissionRepository,
        MessageBusInterface $messageBus,
        MenuRepositoryInterface $repository,
        AliasHelper $aliasHelper
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);

        $this->tokenStorage = $tokenStorage;
        $this->permissionRepository = $permissionRepository;
    }

    public function getMenuByCode(string $code): ?MenuInterface
    {
        return $this->repository->findByCode($code);
    }

    public function getParentMenu(): array
    {
        if (!$group = $this->getGroup()) {
            return [];
        }

        return $this->permissionRepository->findAllowedMenusByGroup($group, true);
    }

    public function hasChildMenu(MenuInterface $menu): bool
    {
        if (!$group = $this->getGroup()) {
            return false;
        }

        $childMenus = $this->permissionRepository->findAllowedChildMenusByGroupAndMenu($group, $menu);
        if (!empty($childMenus)) {
            return true;
        }

        return false;
    }

    public function getChildsMenu(MenuInterface $menu): iterable
    {
        if (!$group = $this->getGroup()) {
            return [];
        }

        return $this->permissionRepository->findAllowedChildMenusByGroupAndMenu($group, $menu);
    }

    private function getGroup(): ?GroupInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$group = $user->getGroup()) {
            return null;
        }

        return $user->getGroup();
    }
}
