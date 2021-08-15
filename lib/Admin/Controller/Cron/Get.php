<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private CronService $service, private AuditService $audit, private Reader $reader)
    {
    }

    /**
     * @Route("/crons/{id}", name=Get::class, methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            $this->addFlash('error', 'sas.page.cron.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        $audit = ['items' => []];
        if ($this->reader->getProvider()->isAuditable(CronØ::class)) {
            $audit = $this->audit->getAudits($cron, $id, 3)->toArray();
        }

        $class = new ReflectionClass(Cron::class);

        return $this->render('cron/view.html.twig', [
            'page_title' => 'sas.page.cron.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $cron,
            'audits' => $audit['items'],
        ]);
    }
}
