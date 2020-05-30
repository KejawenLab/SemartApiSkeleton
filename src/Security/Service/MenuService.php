<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class MenuService extends AbstractService implements ServiceInterface
{
    public function __construct(MenuRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $aliasHelper);
    }
}
