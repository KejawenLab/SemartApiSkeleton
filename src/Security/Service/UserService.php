<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Model\UserRepositoryInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class UserService extends AbstractService implements ServiceInterface
{
    public function __construct(UserRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $aliasHelper);
    }

    public function getByDeviceId(string $deviceId): ?UserInterface
    {
        return $this->repository->findOneBy(['devideId' => $deviceId]);
    }
}
