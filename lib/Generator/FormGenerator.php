<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FormGenerator extends AbstractGenerator
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $properties = $class->getProperties(ReflectionProperty::IS_PRIVATE);
        $formFile = sprintf('%s/app/Form/%sType.php', $this->kernel->getProjectDir(), $shortName);

        if (!$this->fileSystem->exists($formFile)) {
            $template = $this->twig->render('generator/form.php.twig', [
                'entity' => $class,
                'properties' => $properties,
            ]);

            $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\\Application\\Form\\%sType"</info></comment>', $shortName));
            $this->fileSystem->dumpFile($formFile, $template);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $formFile));
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_API === $scope;
    }
}
