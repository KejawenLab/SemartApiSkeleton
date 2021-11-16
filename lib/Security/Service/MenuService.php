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
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuService extends AbstractService implements ServiceInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private PermissionRepositoryInterface $permissionRepository,
        MessageBusInterface $messageBus,
        MenuRepositoryInterface $repository,
        AliasHelper $aliasHelper,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getMenuByCode(string $code): ?MenuInterface
    {
        return $this->repository->findByCode($code);
    }

    public function getParentMenu(): iterable
    {
        if (($group = $this->getGroup()) === null) {
            return [];
        }

        return $this->permissionRepository->findAllowedMenusByGroup($group, true);
    }

    public function hasChildMenu(MenuInterface $menu): bool
    {
        $group = $this->getGroup();
        if (!$group instanceof GroupInterface) {
            return false;
        }

        $childMenus = $this->permissionRepository->findAllowedChildMenusByGroupAndMenu($group, $menu);

        return [] !== iterator_to_array($childMenus, false);
    }

    public function getChildsMenu(MenuInterface $menu): iterable
    {
        if (($group = $this->getGroup()) === null) {
            return [];
        }

        return $this->permissionRepository->findAllowedChildMenusByGroupAndMenu($group, $menu);
    }

    private function getGroup(): ?GroupInterface
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return null;
        }

        return $user->getGroup();
    }
}
