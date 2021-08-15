<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(Setlist::PHP_70);
    $containerConfigurator->import(Setlist::PHP_71);
    $containerConfigurator->import(Setlist::PHP_72);
    $containerConfigurator->import(Setlist::PHP_73);
    $containerConfigurator->import(Setlist::PHP_74);
    $containerConfigurator->import(Setlist::PHP_80);
    $containerConfigurator->import(Setlist::TYPE_DECLARATION);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PATHS, [__DIR__ . '/lib']);


};

