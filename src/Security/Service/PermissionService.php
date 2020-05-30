<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\Semart\ApiSkeleton\Util\Serializer;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionService extends AbstractService implements ServiceInterface
{
    public const FILTER_NAME = 'semart_softdeletable';

    /**
     * @var PermissionInitiatorInterface[] | PermissionRemoverInterface[]
     */
    private $initiators;

    /**
     * @var PermissionRemoverInterface[]
     */
    private $removers;

    private $class;

    public function __construct(
        PermissionRepositoryInterface $repository,
        Serializer $serializer,
        AliasHelper $aliasHelper,
        iterable $initiators,
        iterable $removers,
        string $class
    ) {
        $this->initiators = $initiators;
        $this->removers = $removers;
        $this->class = $class;

        parent::__construct($repository, $serializer, $aliasHelper);
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
        foreach ($this->initiators as $initiator) {
            if ($initiator->support($object)) {
                $initiator->remove($object);
            }
        }
    }
}
