<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Form\CronType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Main extends AbstractController
{
    public function __construct(private CronService $service, Paginator $paginator)
    {
        parent::__construct($this->service, $paginator);
    }

    /**
     * @Route(path="/crons", name=Main::class, methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $cron = new Cron();
        if ($request->isMethod(Request::METHOD_POST)) {
            $cron = $this->service->get($request->getSession()->get('id'));
        } else {
            $flashs = $request->getSession()->getFlashBag()->get('id');
            foreach ($flashs as $flash) {
                $cron = $this->service->get($flash);
                if (null !== $cron) {
                    $request->getSession()->set('id', $cron->getId());

                    break;
                }
            }
        }

        $form = $this->createForm(CronType::class, $cron);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($cron);
                $this->addFlash('info', 'sas.page.cron.saved');
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(Cron::class));
    }
}
