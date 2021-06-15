<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminTemplateGenerator extends AbstractGenerator
{
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $projectDir = $this->kernel->getProjectDir();
        $shortName = $class->getShortName();
        $lowercase = StringUtil::lowercase($shortName);
        $properties = $class->getProperties(ReflectionProperty::IS_PRIVATE);
        $deleteField = sprintf('semart_print(%s)', $shortName);
        $tableFields = '';
        foreach ($properties as $property) {
            if ('id' === $property->getName()) {
                continue;
            }

            $tableFields .= sprintf('<td>{{ semart_print(%s.%s) }}</td>', $lowercase, $property->getName());
        }

        $search = [
            '{# entity | lower #}',
            '{# delete_field #}',
            '{# table_fields #}',
        ];
        $replace = [$lowercase, $deleteField, $tableFields];
        $form = 'templates/generator/admin/view/form.html.stub';
        if ($this->hasAssociation($class)) {
            $form = 'templates/generator/admin/view/form.select2.html.stub';
        }

        $indexTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/all.html.stub', $projectDir)));
        $formTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/%s', $projectDir, $form)));
        $tableTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/view.html.stub', $projectDir)));

        $output->writeln(sprintf('<comment>Generating template <info>"%s/all.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/all.html.twig', $projectDir, $lowercase), $indexTemplate);
        $output->writeln(sprintf('<comment>Generating template <info>"%s/form.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/form.html.twig', $projectDir, $lowercase), $formTemplate);
        $output->writeln(sprintf('<comment>Generating template <info>"%s/view.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/view.html.twig', $projectDir, $lowercase), $tableTemplate);
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_ADMIN === $scope;
    }
}
