<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    /**
     * @Route("/medias/add", methods={"GET"})
     */
    public function __invoke(Request $request)
    {
        return $this->render('media/post.html.twig', [
            'page_title' => 'sas.page.media.add',
            'form' => $this->createForm(UserType::class)->createView(),
        ]);
    }
}
