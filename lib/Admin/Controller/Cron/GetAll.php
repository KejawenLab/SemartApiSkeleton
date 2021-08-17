<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Form\CronType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GetAll extends AbstractController
{
    public function __construct(private CronService $service, private Paginator $paginator)
    {
    }

    /**
     * @Route("/crons", name=GetAll::class, methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new ReflectionClass(Cron::class);
        $cron = new Cron();
        $flashs = $request->getSession()->getFlashBag()->get('id');
        foreach ($flashs as $flash) {
            $cron = $this->service->get($flash);
            if ($cron) {
                $this->addFlash('id', $cron->getId());

                break;
            }
        }

        return $this->render('cron/all.html.twig', [
            'page_title' => 'sas.page.cron.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Cron::class),
            'form' => $this->createForm(CronType::class, $cron)->createView(),
        ]);
    }
}
