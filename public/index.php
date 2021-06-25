<?php

use KejawenLab\ApiSkeleton\Kernel;
use Swoole\Constant;

$root = dirname(__DIR__);

$_SERVER['APP_RUNTIME_OPTIONS'] = [
    'host' => '0.0.0.0',
    'port' => 9501,
    'mode' => SWOOLE_BASE,
    'settings' => [
        Constant::OPTION_WORKER_NUM => swoole_cpu_num() * 2,
        Constant::OPTION_ENABLE_STATIC_HANDLER => true,
        Constant::OPTION_DOCUMENT_ROOT => sprintf('%s/%s', $root, 'public')
    ],
];

require_once sprintf('%s/%s', $root, 'vendor/autoload_runtime.php');

return function (array $context) {
    return new Kernel($context['APP_ENV'], $context['APP_DEBUG']);
};
