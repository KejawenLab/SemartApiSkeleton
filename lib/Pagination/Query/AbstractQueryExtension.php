<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Query;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractQueryExtension implements QueryExtensionInterface
{
    public function __construct(protected AliasHelper $aliasHelper)
    {
    }
}
