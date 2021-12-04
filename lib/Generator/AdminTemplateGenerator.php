<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AdminTemplateGenerator extends AbstractGenerator
{
    public function __construct(
        private readonly Reader $reader,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($twig, $fileSystem, $kernel, $entityManager);
    }

    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $projectDir = $this->kernel->getProjectDir();
        $shortName = $class->getShortName();
        $lowercase = StringUtil::lowercase($shortName);
        $search = [
            '{# entity #}',
            '{# entity | lower #}',
        ];
        $replace = [$shortName, $lowercase];
        $all = 'all.html.stub';
        if ($this->hasAssociation($class)) {
            $all = 'select2-all.html.stub';
        }

        if ($this->reader->getProvider()->isAuditable($class->getName())) {
            $auditTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/audit.html.stub', $projectDir)));
            $this->fileSystem->dumpFile(sprintf('%s/templates/%s/audit.html.twig', $projectDir, $lowercase), $auditTemplate);
        }

        $indexTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/%s', $projectDir, $all)));
        $viewTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/admin/view/view.html.stub', $projectDir)));

        $output->writeln(sprintf('<comment>Generating template <info>"%s/all.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/all.html.twig', $projectDir, $lowercase), $indexTemplate);
        $output->writeln(sprintf('<comment>Generating template <info>"%s/view.html.twig"</info></comment>', $lowercase));
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/view.html.twig', $projectDir, $lowercase), $viewTemplate);
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_ADMIN === $scope;
    }
}
