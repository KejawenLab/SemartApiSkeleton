<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('doctrine.dbal.default_connection');
        $argument = $definition->getArgument(0);
        if (isset($_SERVER['DATABASE_URL']) && $_SERVER['DATABASE_URL']) {
            $argument['url'] = $_SERVER['DATABASE_URL'];
        } else {
            /** @var string $databasePassword */
            $databasePassword = $_SERVER['DATABASE_PASSWORD'];
            /** @var string $appSecret */
            $appSecret = $_SERVER['APP_SECRET'];
            $argument['password'] = Encryptor::decrypt($databasePassword, $appSecret);
        }

        $definition->replaceArgument(0, $argument);

        $definition = $container->getDefinition('twig');
        $definition->addMethodCall('addGlobal', ['sas', [
            'app_title' => $_SERVER['APP_TITLE'],
            'app_description' => $_SERVER['APP_DESCRIPTION'],
            'version' => $_SERVER['APP_VERSION'],
            'media_prefix' => $_SERVER['APP_MEDIA_PREFIX'],
            'semart_name' => 'Semart Api Skeleton',
            'semart_version' => SemartApiSkeleton::VERSION,
        ]]);
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
