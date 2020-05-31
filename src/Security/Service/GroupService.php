<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GroupService extends AbstractService implements ServiceInterface
{
    private $superAdmin;

    public function __construct(GroupRepositoryInterface $repository, AliasHelper $aliasHelper, string $superAdmin)
    {
        $this->superAdmin = $superAdmin;

        parent::__construct($repository, $aliasHelper);
    }

    public function getSuperAdmin(): ?GroupInterface
    {
        return $this->repository->findOneBy(['code' => $this->superAdmin]);
    }
}
