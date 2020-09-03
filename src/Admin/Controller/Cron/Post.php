<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Form\CronType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private CronService $service;

    public function __construct(CronService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/cra/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $cron = new Cron();
        $form = $this->createForm(CronType::class, $cron);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($cron);

                $this->addFlash('info', 'sas.page.cron.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_cron_getall__invoke'));
            }
        }

        return $this->render('cron/form.html.twig', [
            'page_title' => 'sas.page.cron.add',
            'form' => $form->createView(),
        ]);
    }
}
