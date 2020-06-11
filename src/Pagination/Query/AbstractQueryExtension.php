<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Pagination\Query;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Pagination\Model\QueryExtensionInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension implements QueryExtensionInterface
{
    protected AliasHelper $aliasHelper;

    public function __construct(AliasHelper $aliasHelper)
    {
        $this->aliasHelper = $aliasHelper;
    }
}
