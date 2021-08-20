<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AdminControllerGenerator extends AbstractGenerator
{
    private const CONTROLLER_PREFIX = 'KejawenLab\\Application\\Admin\\Controller';

    public function __construct(
        private Reader $reader,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($twig, $fileSystem, $kernel, $entityManager);
    }

    /**
     * @throws RuntimeError
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $getAllFile = sprintf('%s/app/Admin/Controller/%s/Main.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Main"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getAllFile)) {
            $getAll = $this->twig->render('generator/admin/main.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getAllFile, $getAll);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getAllFile));
        }

        $getFile = sprintf('%s/app/Admin/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Get"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getFile)) {
            $get = $this->twig->render('generator/admin/get.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getFile, $get);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getFile));
        }

        $deleteFile = sprintf('%s/app/Admin/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Delete"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($deleteFile)) {
            $delete = $this->twig->render('generator/admin/delete.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($deleteFile, $delete);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $deleteFile));
        }

        $putFile = sprintf('%s/app/Admin/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Put"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($putFile)) {
            $put = $this->twig->render('generator/admin/put.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($putFile, $put);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $putFile));
        }

        $downloadFile = sprintf('%s/app/Admin/Controller/%s/Download.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Download"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($downloadFile)) {
            $put = $this->twig->render('generator/admin/download.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($downloadFile, $put);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $downloadFile));
        }

        if ($this->reader->getProvider()->isAuditable($class->getName())) {
            $audit = $this->twig->render('generator/admin/audit.php.twig', ['entity' => $shortName]);
            $auditFile = sprintf('%s/app/Admin/Controller/%s/Audit.php', $this->kernel->getProjectDir(), $shortName);
            $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Audit"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
            if (!$this->fileSystem->exists($auditFile)) {
                $this->fileSystem->dumpFile($auditFile, $audit);
            } else {
                $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $auditFile));
            }
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_ADMIN === $scope;
    }
}
