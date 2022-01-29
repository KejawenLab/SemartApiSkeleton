<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UserService $service,
        private readonly CacheItemPoolInterface $cache,
        private readonly AuditService $audit,
        private readonly Reader $reader,
    ) {
        parent::__construct($this->requestStack->getCurrentRequest(), $this->service, $this->cache);
    }

    #[Route(path: '/users/{id}/audit', name: Audit::class, methods: ['GET'], priority: -255)]
    public function __invoke(string $id): Response
    {
        if (!$entity = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        if (!$this->reader->getProvider()->isAuditable(User::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        return $this->renderAudit($this->audit->getAudits($entity, $id), new ReflectionClass(User::class));
    }
}
