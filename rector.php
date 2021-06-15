<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Rector\Attribute\ExtractAttributeRouteNameConstantsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // Define what rule sets will be applied
    $containerConfigurator->import(SetList::DEAD_CODE);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    $services->set(ExtractAttributeRouteNameConstantsRector::class);

    $containerConfigurator->import(SetList::PHP_80);

    // register a single rule
    // $services->set(TypedPropertyRector::class);
};
