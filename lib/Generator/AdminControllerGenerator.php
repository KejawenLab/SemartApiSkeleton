<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use DH\Auditor\Provider\Doctrine\Auditing\Annotation\Auditable;
use Doctrine\Common\Annotations\Reader;
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

    public function __construct(Reader $reader, Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        parent::__construct($twig, $fileSystem, $kernel);

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

        if ($this->reader->getClassAnnotation($class, Auditable::class)) {
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
