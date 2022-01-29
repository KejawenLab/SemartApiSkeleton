<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\MediaType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MEDIA', actions: [Permission::VIEW])]
final class Main extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly MediaService $service,
        private readonly CacheItemPoolInterface $cache,
        private readonly Paginator $paginator,
    ) {
        parent::__construct($this->requestStack->getCurrentRequest(), $this->service, $this->cache, $this->paginator);
    }
    #[Route(path: '/medias', name: Main::class, methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($form->getData());
                $this->addFlash('info', 'sas.page.media.saved');
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(Media::class));
    }
}
