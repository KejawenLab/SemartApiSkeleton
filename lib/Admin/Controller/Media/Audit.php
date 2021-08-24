<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit extends AbstractController
{
    public function __construct(private MediaService $service, private AuditService $audit, private Reader $reader)
    {
        parent::__construct($this->service);
    }

    /**
     * @Route(path="/medias/{id}/audit", name=Audit::class, methods={"GET"}, priority=1)
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(string $id): Response
    {
        $entity = $this->service->get($id);
        if (!$entity instanceof MediaInterface) {
            $this->addFlash('error', 'sas.page.media.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        if (!$this->reader->getProvider()->isAuditable(Media::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        return $this->renderAudit($this->audit->getAudits($entity, $id), new ReflectionClass(Media::class));
    }
}
