<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;

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
