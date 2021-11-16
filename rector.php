<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Core\Configuration\Option;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(Setlist::PHP_70);
    $containerConfigurator->import(Setlist::PHP_71);
    $containerConfigurator->import(Setlist::PHP_72);
    $containerConfigurator->import(Setlist::PHP_73);
    $containerConfigurator->import(Setlist::PHP_74);
    $containerConfigurator->import(Setlist::PHP_80);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(Setlist::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);

    $services = $containerConfigurator->services();
    $services->set(AnnotationToAttributeRector::class)
        ->call('configure', [[
            AnnotationToAttributeRector::ANNOTATION_TO_ATTRIBUTE => ValueObjectInliner::inline([
                new AnnotationToAttribute(
                    'Symfony\Component\Routing\Annotation\Route',
                    'Symfony\Component\Routing\Annotation\Route',
                ),
                new AnnotationToAttribute(
                    'FOS\RestBundle\Controller\Annotations\Get',
                    'FOS\RestBundle\Controller\Annotations\Get',
                ),
                new AnnotationToAttribute(
                    'FOS\RestBundle\Controller\Annotations\Post',
                    'FOS\RestBundle\Controller\Annotations\Post',
                ),
                new AnnotationToAttribute(
                    'FOS\RestBundle\Controller\Annotations\Put',
                    'FOS\RestBundle\Controller\Annotations\Put',
                ),
                new AnnotationToAttribute(
                    'FOS\RestBundle\Controller\Annotations\Delete',
                    'FOS\RestBundle\Controller\Annotations\Delete',
                ),
            ]),
        ]])
    ;

    // tidify lines after apply annotation to attribute that make remove lines
    // this may need to run rector twice as first is apply the AnnotationToAttributeRector
    $services->set(NewlineAfterStatementRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PATHS, [__DIR__ . '/lib']);

    $parameters->set(Option::SKIP, [
        // follow convention, eg: Twig extension @see https://symfony.com/doc/current/templating/twig_extension.html
        CallableThisArrayToAnonymousFunctionRector::class,

        AddArrayReturnDocTypeRector::class => [
            // mostly class-string[] is enough for collection of class-string return
            __DIR__ . '/lib/DataFixtures/',
        ],
    ]);
};

