<?php

use KejawenLab\ApiSkeleton\CachedKernel;
use KejawenLab\ApiSkeleton\Kernel;
use Swoole\Constant;

$root = dirname(__DIR__);

$_SERVER['APP_RUNTIME_OPTIONS'] = [
    'host' => '0.0.0.0',
    'port' => 9501,
    'mode' => SWOOLE_PROCESS,
    'settings' => [
        Constant::OPTION_WORKER_NUM => swoole_cpu_num() * 2,
        Constant::OPTION_ENABLE_STATIC_HANDLER => true,
        Constant::OPTION_DOCUMENT_ROOT => sprintf('%s/%s', $root, 'public'),
        Constant::OPTION_HTTP_COMPRESSION => true,
        Constant::OPTION_COMPRESSION_MIN_LENGTH => 20,
        Constant::OPTION_HTTP_COMPRESSION_LEVEL => 7,
        Constant::OPTION_OPEN_HTTP2_PROTOCOL => true,
    ],
];

require_once sprintf('%s/%s', $root, 'vendor/autoload_runtime.php');

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], $context['APP_DEBUG']);
    if ('prod' === $kernel->getEnvironment()) {
        $kernel = new CachedKernel($kernel);
    }

    return $kernel;
};
