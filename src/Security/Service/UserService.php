<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class UserService extends AbstractService implements ServiceInterface
{
    public function __construct(UserRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $aliasHelper);
    }
}
