<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionableInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionService extends AbstractService implements ServiceInterface
{
    public const FILTER_NAME = 'semart_softdeletable';

    private $menuRepository;

    /**
     * @var PermissionInitiatorInterface[]
     */
    private $initiators;

    /**
     * @var PermissionRemoverInterface[]
     */
    private $removers;

    private $class;

    public function __construct(
        MessageBusInterface $messageBus,
        PermissionRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        MenuRepositoryInterface $menuRepository,
        iterable $initiators,
        iterable $removers,
        string $class
    ) {
        $this->menuRepository = $menuRepository;
        $this->initiators = $initiators;
        $this->removers = $removers;
        $this->class = $class;

        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function initiate(PermissionableInterface $object): void
    {
        foreach ($this->initiators as $initiator) {
            if ($initiator->support($object)) {
                $initiator->setClass($this->class);
                $initiator->initiate($object);
            }
        }
    }

    public function revoke(PermissionableInterface $object): void
    {
        foreach ($this->removers as $remover) {
            if ($remover->support($object)) {
                $remover->remove($object);
            }
        }
    }

    public function getPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface
    {
        return $this->repository->findPermission($group, $menu);
    }

    public function getByUser(UserInterface $user): array
    {
        /** @var MenuInterface[] $parents */
        $parents = $this->repository->findAllowedMenusByGroup($user->getGroup(), true);
        $menus = [];
        foreach ($parents as $key => $parent) {
            $menus[$key] = $this->buildMenu($parent, $user->getGroup());
        }

        return $menus;
    }

    private function buildMenu(MenuInterface $menu, GroupInterface $group): array
    {
        $tree = [
            'id' => $menu->getId(),
            'code' => $menu->getCode(),
            'name' => $menu->getName(),
            'path' => $menu->getUrlPath(),
            'extra' => $menu->getExtra(),
        ];

        /** @var MenuInterface[] $childs */
        $childs = $this->menuRepository->findChilds($menu);
        if (!empty($childs)) {
            $tree['childs'] = [];
            foreach ($childs as $key => $child) {
                $permission = $this->getPermission($group, $child);
                if ($permission && ($permission->isViewable() || $permission->isAddable() || $permission->isEditable())) {
                    $tree['childs'][$key] = $this->buildMenu($child, $group);
                }
            }
        }

        return $tree;
    }
}
