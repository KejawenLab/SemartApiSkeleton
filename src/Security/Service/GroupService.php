<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\Semart\ApiSkeleton\Util\Serializer;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class GroupService extends AbstractService implements ServiceInterface
{
    public function __construct(GroupRepositoryInterface $repository, Serializer $serializer, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $serializer, $aliasHelper);
    }
}
