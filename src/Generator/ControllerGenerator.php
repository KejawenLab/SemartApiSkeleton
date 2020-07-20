<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use DH\DoctrineAuditBundle\Annotation\Auditable;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ControllerGenerator extends AbstractGenerator
{
    private Reader $reader;

    public function __construct(Reader $reader, Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        parent::__construct($twig, $fileSystem, $kernel);

        $this->reader = $reader;
    }

    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $getAllFile = sprintf('%s/src/Controller/%s/GetAll.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\GetAll"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($getAllFile)) {
            $getAll = $this->twig->render('generator/get_all.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getAllFile, $getAll);
        } else {
            $output->write(sprintf('<info>File "%s" is exists. Skipped</info>', $getAllFile));
        }

        $getFile = sprintf('%s/src/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\Get"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($getFile)) {
            $get = $this->twig->render('generator/get.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getFile, $get);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $getFile));
        }

        $deleteFile = sprintf('%s/src/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\Delete"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($deleteFile)) {
            $delete = $this->twig->render('generator/delete.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($deleteFile, $delete);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $deleteFile));
        }

        $postFile = sprintf('%s/src/Controller/%s/Post.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\Post"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($postFile)) {
            $post = $this->twig->render('generator/post.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($postFile, $post);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $postFile));
        }

        $putFile = sprintf('%s/src/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\Put"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($putFile)) {
            $put = $this->twig->render('generator/put.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($putFile, $put);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $putFile));
        }

        if ($this->reader->getClassAnnotation($class, Auditable::class)) {
            $audit = $this->twig->render('generator/audit.php.twig', ['entity' => $shortName]);
            $auditFile = sprintf('%s/src/Controller/%s/Audit.php', $this->kernel->getProjectDir(), $shortName);
            $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Controller\%s\Audit"</info></comment>', $shortName));
            if (!$this->fileSystem->exists($auditFile)) {
                $this->fileSystem->dumpFile($auditFile, $audit);
            } else {
                $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $auditFile));
            }
        }
    }
}
