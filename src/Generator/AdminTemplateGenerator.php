<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminTemplateGenerator extends AbstractGenerator
{
    public function __construct(Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        parent::__construct($twig, $fileSystem, $kernel);
    }

    public function generate(\ReflectionClass $entityClass, OutputInterface $output, ?string $folder): void
    {
        $projectDir = $this->kernel->getProjectDir();
        $shortName = $entityClass->getShortName();
        $lowercase = StringUtil::lowercase($shortName);
        $properties = $entityClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $deleteField = '';
        $tableFields = '';
        foreach ($properties as $property) {
            if ('id' === $property->getName()) {
                continue;
            }

            if (!$deleteField) {
                $deleteField = sprintf('%s.%s', $lowercase, $property->getName());
            }

            $tableFields .= sprintf('<td>{{ %s.%s }}</td>', $lowercase, $property->getName());
        }

        $search = [
            '{# entity | lower #}',
            '{# delete_field #}',
            '{# table_fields #}',
        ];
        $replace = [$lowercase, $deleteField, $tableFields];

        $indexTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/all.html.stub', $projectDir)));
        $paginationTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/form.html.stub', $projectDir)));
        $tableTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/view.html.stub', $projectDir)));

        $output->writeln(sprintf('<comment>Generating template <info>"%s/all.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/all.html.twig', $projectDir, $lowercase), $indexTemplate);
        $output->writeln(sprintf('<comment>Generating template <info>"%s/form.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/form.html.twig', $projectDir, $lowercase), $paginationTemplate);
        $output->writeln(sprintf('<comment>Generating template <info>"%s/view.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/view.html.twig', $projectDir, $lowercase), $tableTemplate);
    }

    public function support(string $scope): bool
    {
        return static::SCOPE_ADMIN === $scope;
    }
}
