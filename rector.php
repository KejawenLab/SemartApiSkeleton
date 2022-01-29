<?php

declare(strict_types=1);

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Core\Configuration\Option;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
//    $containerConfigurator->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
//    $containerConfigurator->import(SetList::CODE_QUALITY);
//    $containerConfigurator->import(SetList::DEAD_CODE);
//    $containerConfigurator->import(LevelSetList::UP_TO_PHP_81);
//    $containerConfigurator->import(SetList::EARLY_RETURN);
//    $containerConfigurator->import(Setlist::TYPE_DECLARATION);
//    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
//    $containerConfigurator->import(SymfonyLevelSetList::UP_TO_SYMFONY_60);

    $services = $containerConfigurator->services();
    $openApiAnnotationsRaw = [
        'AdditionalProperties',
        'Attachable',
        'Components',
        'Contact',
        'Delete',
        'Discriminator',
        'Examples',
        'ExternalDocumentation',
        'Flow',
        'Get',
        'Head',
        'Header',
        'Info',
        'Items',
        'JsonContent',
        'License',
        'Link',
        'MediaType',
        'OpenApi',
        'Operation',
        'Options',
        'Parameter',
        'Patch',
        'PatchItem',
        'PathParameter',
        'Post',
        'Property',
        'Put',
        'RequestBody',
        'Response',
        'Schema',
        'SecurityScheme',
        'Server',
        'ServerVariable',
        'Tag',
        'Trace',
        'Xml',
        'XmlContent',
    ];

    $openApiAnnotations = [];
    foreach ($openApiAnnotationsRaw as $className) {
        $openApiAnnotations[] = new \Rector\Php80\ValueObject\AnnotationToAttribute(
            'OpenApi\\Annotations\\' . $className,
            'OpenApi\\Attributes\\' . $className
        );
    }

    $services->set(AnnotationToAttributeRector::class)->configure($openApiAnnotations);
    // tidify lines after apply annotation to attribute that make remove lines
    // this may need to run rector twice as first is apply the AnnotationToAttributeRector
//    $services->set(NewlineAfterStatementRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PATHS, [__DIR__ . '/lib']);
//
//    $parameters->set(Option::SKIP, [
//        // follow convention, eg: Twig extension @see https://symfony.com/doc/current/templating/twig_extension.html
//        CallableThisArrayToAnonymousFunctionRector::class,
//
//        AddArrayReturnDocTypeRector::class => [
//            // mostly class-string[] is enough for collection of class-string return
//            __DIR__ . '/lib/DataFixtures',
//        ],
//    ]);
};

