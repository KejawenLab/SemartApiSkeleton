<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Client;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Client\ClientService;
use KejawenLab\ApiSkeleton\Client\Model\ClientInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="CLIENT", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    private ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Delete("/clients/{id}")
     *
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=204,
     *     description="Delete client"
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, string $id): View
    {
        $client = $this->service->get($id);
        if (!$client instanceof ClientInterface) {
            throw new NotFoundHttpException(sprintf('Client ID: "%s" not found', $id));
        }

        $this->service->remove($client);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
