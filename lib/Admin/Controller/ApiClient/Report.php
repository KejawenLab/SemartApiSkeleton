<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\Admin\Controller\User\Main as GetAllUser;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="APICLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Report extends AbstractController
{
    public function __construct(private ApiClientRequestService $service, private UserService $userService, private Paginator $paginator)
    {
    }

    /**
     * @Route(path="/users/{userId}/api-clients/{id}/logs", name=Report::class, methods={"GET"})
     */
    public function __invoke(Request $request, string $userId, string $id): Response
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(GetAllUser::class));
        }

        $class = new ReflectionClass(ApiClientRequest::class);

        return $this->render('apiclient/report.html.twig', [
            'page_title' => 'sas.page.api_client.report',
            'id' => $id,
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClientRequest::class),
            'user_id' => $userId,
        ]);
    }
}
