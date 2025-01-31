<?php

use KejawenLab\ApiSkeleton\CachedKernel;
use KejawenLab\ApiSkeleton\Kernel;

$root = dirname(__DIR__);

require_once sprintf('%s/%s', $root, 'vendor/autoload_runtime.php');

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], $context['APP_DEBUG']);
    if ('prod' === $kernel->getEnvironment()) {
        $kernel = new CachedKernel($kernel);
    }

    return $kernel;
};
