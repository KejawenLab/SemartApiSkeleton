<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Entity\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Entity\Message\EntityRemoved;
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
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    private const FILTER_NAME = 'semart_softdeletable';

    private EntityManagerInterface $entityManager;

    private MenuRepositoryInterface $menuRepository;

    /**
     * @var PermissionInitiatorInterface[]
     */
    private iterable $initiators;

    /**
     * @var PermissionRemoverInterface[]
     */
    private iterable $removers;

    private string $class;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        PermissionRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        MenuRepositoryInterface $menuRepository,
        iterable $initiators,
        iterable $removers,
        string $class
    ) {
        $this->entityManager = $entityManager;
        $this->menuRepository = $menuRepository;
        $this->initiators = $initiators;
        $this->removers = $removers;
        $this->class = $class;

        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(EntityPersisted $message): void
    {
        $entity = $message->getEntity();
        if (!$entity instanceof PermissionableInterface) {
            return;
        }

        if (EntityPersisted::class === get_class($message)) {
            $this->initiate($entity);
        } else {
            $this->revoke($entity);
        }
    }

    public function initiate(PermissionableInterface $object): void
    {
        $this->entityManager->getFilters()->disable(static::FILTER_NAME);
        foreach ($this->initiators as $initiator) {
            if ($initiator->support($object)) {
                $initiator->setClass($this->class);
                $initiator->initiate($object);
            }
        }

        $this->entityManager->getFilters()->enable(static::FILTER_NAME);
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

    public function getPermissions(GroupInterface $group, iterable $menus): iterable
    {
        return $this->repository->findPermissions($group, $menus);
    }

    public function getByUser(AuthInterface $user): iterable
    {
        /** @var MenuInterface[] $parents */
        $parents = $this->repository->findAllowedMenusByGroup($user->getGroup(), true);
        foreach ($parents as $key => $parent) {
            yield $this->buildMenu($parent, $user->getGroup());
        }
    }

    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
        yield EntityRemoved::class;
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
            $permissions = $this->getPermissions($group, $childs);
            foreach ($permissions as $key => $permission) {
                /** @var PermissionInterface $permission */
                if ($permission && ($permission->isViewable() || $permission->isAddable() || $permission->isEditable())) {
                    $tree['childs'][$key] = $this->buildMenu($permission->getMenu(), $permission->getGroup());
                }
            }
        }

        return $tree;
    }
}
