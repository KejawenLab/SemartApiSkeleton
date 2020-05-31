<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ControllerGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $getAll = $this->twig->render('generator/get_all.php.twig', ['entity' => $shortName]);
        $get = $this->twig->render('generator/get.php.twig', ['entity' => $shortName]);
        $delete = $this->twig->render('generator/delete.php.twig', ['entity' => $shortName]);
        $post = $this->twig->render('generator/post.php.twig', ['entity' => $shortName]);
        $put = $this->twig->render('generator/put.php.twig', ['entity' => $shortName]);

        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Controller\%s\GetAll"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/GetAll.php', $this->kernel->getProjectDir(), $shortName), $getAll);
        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Controller\%s\Get"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName), $get);
        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Controller\%s\Delete"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName), $delete);
        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Controller\%s\Post"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Post.php', $this->kernel->getProjectDir(), $shortName), $post);
        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Controller\%s\Put"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName), $put);
    }
}
