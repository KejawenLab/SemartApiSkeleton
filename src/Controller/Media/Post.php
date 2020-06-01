<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Entity\Media;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\MediaType;
use Alpabit\ApiSkeleton\Media\MediaService;
use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MEDIA", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class Post extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    private $logger;

    public function __construct(FormFactory $formFactory, MediaService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
    * @Rest\Post("/medias")
    *
    * @SWG\Tag(name="Media")
    * @SWG\Post(consumes={"multipart/form-data"})
    * @SWG\Parameter(
    *     name="file",
    *     in="formData",
    *     type="file",
    *     description="File to upload"
    * )
     * @SWG\Parameter(
     *     name="public",
     *     in="formData",
     *     type="integer",
     *     description="Is public"
     * )
    * @SWG\Response(
    *     response=201,
    *     description="Crate new media",
    *     @SWG\Schema(
    *         type="object",
    *         ref=@Model(type=Media::class, groups={"read"})
    *     )
    * )
    *
    * @Security(name="Bearer")
    *
    * @param Request $request
    *
    * @return View
    */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(MediaType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var MediaInterface $media */
        $media = $form->getData();
        $this->service->save($media);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $request->getContent()));

        return $this->view($this->service->get($media->getId()), Response::HTTP_CREATED);
    }
}
