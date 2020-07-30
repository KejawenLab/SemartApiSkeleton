<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\MediaType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/medias/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request)
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($media);

                $this->addFlash('info', 'sas.page.media.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_media_getall__invoke'));
            }
        }

        return $this->render('media/form.html.twig', [
            'page_title' => 'sas.page.media.add',
            'form' => $form->createView(),
        ]);
    }
}
