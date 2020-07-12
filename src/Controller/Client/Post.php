<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Client;

use Alpabit\ApiSkeleton\Client\ClientService;
use Alpabit\ApiSkeleton\Entity\Client;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\UserProviderFactory;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
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
     *         ref=@Model(type=Client::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request, UserProviderFactory $userProviderFactory): View
    {
        $client = new Client();
        $client->setUser($userProviderFactory->getRealUser($this->getUser()));

        $this->service->save($client);

        return $this->view($this->service->get($client->getId()), Response::HTTP_CREATED);
    }
}
