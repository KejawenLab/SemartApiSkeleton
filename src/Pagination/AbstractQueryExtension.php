<?php

declare(strict_types=1);

namespace App\Pagination;

use App\Pagination\Model\QueryExtensionInterface;

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
