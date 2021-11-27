<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="APICLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Report extends AbstractFOSRestController
{
    public function __construct(
        private ApiClientRequestService $service,
        private UserService $userService,
        private Paginator $paginator,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @Cache(expires="+1 minute", public=false)
     *
     * @OA\Tag(name="Api Client")
     * @OA\Response(
     *     response=200,
     *     description= "Api client request list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=ApiClientRequest::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Get(data: '/users/{userId}/api-clients/{id}/logs', name: Report::class, priority: -27)]
    public function __invoke(Request $request, string $userId, string $id): View
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClientRequest::class));
    }
}
