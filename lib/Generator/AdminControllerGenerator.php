<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminControllerGenerator extends AbstractGenerator
{
    private const CONTROLLER_PREFIX = 'KejawenLab\\ApiSkeleton\\Application\\Admin\\Controller';

    private Reader $reader;

    public function __construct(Reader $reader, Environment $twig, Filesystem $fileSystem, KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        parent::__construct($twig, $fileSystem, $kernel, $entityManager);

        $this->reader = $reader;
    }

    public function generate(\ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $getAllFile = sprintf('%s/app/Admin/Controller/%s/GetAll.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\GetAll"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getAllFile)) {
            $getAll = $this->twig->render('generator/admin/get_all.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getAllFile, $getAll);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getAllFile));
        }

        $getFile = sprintf('%s/app/Admin/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Get"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getFile)) {
            $get = $this->twig->render('generator/admin/get.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getFile, $get);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getFile));
        }

        $deleteFile = sprintf('%s/app/Admin/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Delete"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($deleteFile)) {
            $delete = $this->twig->render('generator/admin/delete.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($deleteFile, $delete);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $deleteFile));
        }

        $postFile = sprintf('%s/app/Admin/Controller/%s/Post.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Post"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($postFile)) {
            $post = $this->twig->render('generator/admin/post.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($postFile, $post);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $postFile));
        }

        $putFile = sprintf('%s/app/Admin/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Put"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($putFile)) {
            $put = $this->twig->render('generator/admin/put.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($putFile, $put);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $putFile));
        }

        $downloadFile = sprintf('%s/app/Admin/Controller/%s/Download.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Download"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($downloadFile)) {
            $put = $this->twig->render('generator/admin/download.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($downloadFile, $put);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $downloadFile));
        }

        if ($this->reader->getProvider()->isAuditable($class->getName())) {
            $audit = $this->twig->render('generator/admin/audit.php.twig', ['entity' => $shortName]);
            $auditFile = sprintf('%s/app/Admin/Controller/%s/Audit.php', $this->kernel->getProjectDir(), $shortName);
            $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Audit"</info></comment>', static::CONTROLLER_PREFIX, $shortName));
            if (!$this->fileSystem->exists($auditFile)) {
                $this->fileSystem->dumpFile($auditFile, $audit);
            } else {
                $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $auditFile));
            }
        }
    }

    public function support(string $scope): bool
    {
        return static::SCOPE_ADMIN === $scope;
    }
}
