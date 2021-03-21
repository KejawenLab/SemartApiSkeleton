<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="APICLIENT", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private ApiClientService $service;

    public function __construct(FormFactory $formFactory, ApiClientService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Post("/api-clients")
     *
     * @SWG\Tag(name="Api Client")
     * @SWG\RequestBody(
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=ApiClientType::class)
     *     ),
     *     description="Api client form"
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Crate new api client",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=ApiClient::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, UserProviderFactory $userProviderFactory): View
    {
        $form = $this->formFactory->submitRequest(ApiClientType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var ApiClientInterface $client */
        $client = $form->getData();
        $this->service->save($client);

        return $this->view($this->service->get($client->getId()), Response::HTTP_CREATED);
    }
}
