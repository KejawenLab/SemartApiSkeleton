<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Rector\Attribute\ExtractAttributeRouteNameConstantsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $containerConfigurator->import(SetList::DEAD_CODE);

    $services = $containerConfigurator->services();
    $services->set(ExtractAttributeRouteNameConstantsRector::class);

    $containerConfigurator->import(SetList::PHP_80);
};
