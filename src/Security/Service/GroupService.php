<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Pagination\AliasHelper;
use App\Security\Model\GroupRepositoryInterface;
use App\Service\AbstractService;
use App\Service\Model\ServiceInterface;
use App\Util\Serializer;

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
