<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Audit\Audit as Record;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private UserService $service, private AuditService $audit, private Reader $reader)
    {
        parent::__construct($this->service);
    }

    #[Route(path: '/users/{id}', name: Get::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }
        $audit = new Record($user);
        if ($this->reader->getProvider()->isAuditable(User::class)) {
            $audit = $this->audit->getAudits($user, $id, 1);
        }

        return $this->renderDetail($audit, new ReflectionClass(User::class));
    }
}
