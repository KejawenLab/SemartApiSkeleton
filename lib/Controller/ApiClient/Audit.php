<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'AUDIT', actions: [Permission::VIEW])]
final class Audit extends AbstractFOSRestController
{
    public function __construct(
        private readonly ApiClientService $service,
        private readonly UserService $userService,
        private readonly AuditService $audit,
        private readonly Reader $reader,
        private readonly TranslatorInterface $translator,
    ) {
    }
    #[Get(data: '/users/{userId}/api-clients/{id}/audit', name: Audit::class, priority: -255)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Api Client')]
    #[Response(response: 200, description: 'Audit list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(properties: [new OA\Property(property: 'entity', type: 'object', new OA\Schema(type: 'object', ref: new Model(type: ApiClient::class, groups: ['read']))), new OA\Property(type: 'string', property: 'type'), new OA\Property(type: 'string', property: 'user_id'), new OA\Property(type: 'string', property: 'username'), new OA\Property(type: 'string', property: 'ip_address'), new OA\Property(type: 'array', property: 'data', new OA\Items(new OA\Property(type: 'string', property: 'new'), new OA\Property(type: 'string', property: 'old')))])))])]
    public function __invoke(string $userId, string $id): View
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(ApiClient::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
