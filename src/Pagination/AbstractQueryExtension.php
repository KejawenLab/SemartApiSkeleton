<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Pagination;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\QueryExtensionInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension implements QueryExtensionInterface
{
    protected $aliasHelper;

    public function __construct(AliasHelper $aliasHelper)
    {
        $this->aliasHelper = $aliasHelper;
    }
}
