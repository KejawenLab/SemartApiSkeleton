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
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    public function __construct(private MediaService $service)
    {
    }

    /**
     * @Route("/medias/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($media);

                $this->addFlash('info', 'sas.page.media.saved');

                return new RedirectResponse($this->generateUrl(GetAll::class));
            }
        }

        return $this->render('media/form.html.twig', [
            'page_title' => 'sas.page.media.add',
            'form' => $form->createView(),
        ]);
    }
}
