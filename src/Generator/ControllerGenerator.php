<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ControllerGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $getAll = $this->twig->render('generator/get_all.php.twig', ['entity' => $shortName]);
        $get = $this->twig->render('generator/get.php.twig', ['entity' => $shortName]);
        $delete = $this->twig->render('generator/delete.php.twig', ['entity' => $shortName]);
        $post = $this->twig->render('generator/post.php.twig', ['entity' => $shortName]);
        $put = $this->twig->render('generator/put.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/GetAll.php', $this->kernel->getProjectDir(), $shortName), $getAll);
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Get.php', $this->kernel->getProjectDir(), $shortName), $get);
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Delete.php', $this->kernel->getProjectDir(), $shortName), $delete);
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Post.php', $this->kernel->getProjectDir(), $shortName), $post);
        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/%s/Put.php', $this->kernel->getProjectDir(), $shortName), $put);
    }
}
