<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Client;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Client\ClientService;
use KejawenLab\ApiSkeleton\Client\Model\ClientInterface;
use KejawenLab\ApiSkeleton\Form\ClientType;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="CLIENT", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private ClientService $service;

    public function __construct(FormFactory $formFactory, ClientService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Post("/clients")
     *
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=201,
     *     description="Crate new client",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=ClientType::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, UserProviderFactory $userProviderFactory): View
    {
        $form = $this->formFactory->submitRequest(ClientType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var ClientInterface $client */
        $client = $form->getData();
        $this->service->save($client);

        return $this->view($this->service->get($client->getId()), Response::HTTP_CREATED);
    }
}
