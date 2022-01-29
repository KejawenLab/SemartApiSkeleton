<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use Doctrine\ORM\EntityManagerInterface;
use Iterator;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Service\Message\EntityRemoved;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    private const FILTER_NAME = 'semart_softdeletable';

    /**
     * @param PermissionInitiatorInterface[] $initiators
     * @param PermissionRemoverInterface[] $removers
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        PermissionRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private readonly MenuRepositoryInterface $menuRepository,
        private readonly iterable $initiators,
        private readonly iterable $removers,
        private readonly string $class,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(EntityPersisted $message): void
    {
        $entity = $message->getEntity();
        if (!$entity instanceof PermissionableInterface) {
            return;
        }

        if (EntityPersisted::class === $message::class) {
            $this->initiate($entity);
        } else {
            $this->revoke($entity);
        }
    }

    public function initiate(PermissionableInterface $object): void
    {
        $this->entityManager->getFilters()->disable(self::FILTER_NAME);
        foreach ($this->initiators as $initiator) {
            if ($initiator->support($object)) {
                $initiator->setClass($this->class);
                $initiator->initiate($object);
            }
        }

        $this->entityManager->getFilters()->enable(self::FILTER_NAME);
    }

    public function revoke(PermissionableInterface $object): void
    {
        foreach ($this->removers as $remover) {
            if ($remover->support($object)) {
                $remover->remove($object);
            }
        }
    }

    public function getPermission(GroupInterface $group, MenuInterface $menu, bool $cached = true): ?PermissionInterface
    {
        return $this->repository->findPermission($group, $menu, $cached);
    }

    /**
     * @return Iterator<array>
     */
    public function getByUser(AuthInterface $user): iterable
    {
        /** @var MenuInterface[] $parents */
        $parents = $this->repository->findAllowedMenusByGroup($user->getGroup(), true);
        foreach ($parents as $parent) {
            yield $this->buildMenu($parent, $user->getGroup());
        }
    }

    /**
     * @return Iterator<string>
     */
    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
        yield EntityRemoved::class;
    }

    private function getPermissions(GroupInterface $group, iterable $menus): iterable
    {
        return $this->repository->findPermissions($group, $menus);
    }

    /**
     * @return array<string, array>
     */
    private function buildMenu(MenuInterface $menu, GroupInterface $group): array
    {
        $tree = [
            'id' => $menu->getId(),
            'code' => $menu->getCode(),
            'name' => $menu->getName(),
            'path' => $menu->getApiPath(),
        ];

        /** @var MenuInterface[] $childs */
        $childs = $this->menuRepository->findChilds($menu);
        $tree['childs'] = [];
        $permissions = $this->getPermissions($group, $childs);
        $key = 0;
        foreach ($permissions as $permission) {
            /** @var PermissionInterface $permission */
            if ($permission && ($permission->isViewable() || $permission->isAddable() || $permission->isEditable())) {
                $tree['childs'][$key] = $this->buildMenu($permission->getMenu(), $permission->getGroup());
            }

            ++$key;
        }

        return $tree;
    }
}
