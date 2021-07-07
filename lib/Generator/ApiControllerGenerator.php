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
final class ApiControllerGenerator extends AbstractGenerator
{
    private const CONTROLLER_PREFIX = 'KejawenLab\\Application\\Controller';

    public function __construct(
        private Reader $reader,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        EntityManagerInterface $entityManager
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
        $getAllFile = sprintf('%s/app/Controller/%s/GetAll.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\GetAll"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getAllFile)) {
            $getAll = $this->twig->render('generator/api/get_all.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getAllFile, $getAll);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getAllFile));
        }

        $getFile = sprintf('%s/app/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Get"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($getFile)) {
            $get = $this->twig->render('generator/api/get.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($getFile, $get);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $getFile));
        }

        $deleteFile = sprintf('%s/app/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Delete"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($deleteFile)) {
            $delete = $this->twig->render('generator/api/delete.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($deleteFile, $delete);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $deleteFile));
        }

        $postFile = sprintf('%s/app/Controller/%s/Post.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Post"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($postFile)) {
            $post = $this->twig->render('generator/api/post.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($postFile, $post);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $postFile));
        }

        $putFile = sprintf('%s/app/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"%s\\%s\\Put"</info></comment>', self::CONTROLLER_PREFIX, $shortName));
        if (!$this->fileSystem->exists($putFile)) {
            $put = $this->twig->render('generator/api/put.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($putFile, $put);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $putFile));
        }

        if ($this->reader->getProvider()->isAuditable($class->getName())) {
            $audit = $this->twig->render('generator/api/audit.php.twig', ['entity' => $shortName]);
            $auditFile = sprintf('%s/app/Controller/%s/Audit.php', $this->kernel->getProjectDir(), $shortName);
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
        return self::SCOPE_API === $scope;
    }
}
