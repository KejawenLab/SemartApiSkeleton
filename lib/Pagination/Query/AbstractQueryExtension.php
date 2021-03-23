<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Query;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractQueryExtension implements QueryExtensionInterface
{
    protected AliasHelper $aliasHelper;

    public function __construct(AliasHelper $aliasHelper)
    {
        $this->aliasHelper = $aliasHelper;
    }
}
