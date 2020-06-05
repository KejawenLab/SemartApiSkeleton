<?php

namespace Alpabit\ApiSkeleton;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class CachedKernel extends HttpCache
{
    protected function getOptions()
    {
        return [
            'default_ttl' => 3,
            'stale_if_error' => 7,
        ];
    }
}
