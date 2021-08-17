<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\MediaType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MEDIA", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Post extends AbstractController
{
    public function __construct(private MediaService $service)
    {
    }

    /**
     * @Route("/medias", name=Post::class, methods={"POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $media = new Media();
        $flashs = $request->getSession()->getFlashBag()->get('id');
        foreach ($flashs as $flash) {
            $media = $this->service->get($flash);

            break;
        }

        $form = $this->createForm(MediaType::class, $media);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($media);
                $this->addFlash('info', 'sas.page.media.saved');
            }
        }

        return new RedirectResponse($this->generateUrl(GetAll::class));
    }
}
