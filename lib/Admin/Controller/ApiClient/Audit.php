<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Admin\Controller\User\GetAll as GetAllUser;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
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
    public function __construct(private ApiClientService $service, private UserService $userService, private AuditService $audit, private Reader $reader)
    {
        parent::__construct($this->service);
    }

    /**
     * @Route("users/{userId}/api-clients/{id}/audit", name=Audit::class, methods={"GET"}, priority=-255)
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(string $userId, string $id): Response
    {
        $user = $this->userService->get($userId);
        if (!$user) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(GetAllUser::class));
        }

        if (!$entity = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.api_client.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        if (!$this->reader->getProvider()->isAuditable(Group::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        return $this->renderAudit($this->audit->getAudits($entity, $id), new ReflectionClass(ApiClient::class));
    }
}
