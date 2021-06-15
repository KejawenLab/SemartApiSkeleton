<?php

namespace KejawenLab\ApiSkeleton;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

final class CachedKernel extends HttpCache
{
    protected function getOptions(): array
    {
        return [
            'default_ttl' => 3,
            'stale_if_error' => 7,
        ];
    }
}
