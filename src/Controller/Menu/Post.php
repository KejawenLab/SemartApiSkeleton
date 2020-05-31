<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Menu;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Entity\Menu;
use KejawenLab\Semart\ApiSkeleton\Form\FormFactory;
use KejawenLab\Semart\ApiSkeleton\Form\Type\MenuType;
use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MENU", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Post extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    private $logger;

    public function __construct(FormFactory $formFactory, MenuService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Post("/menus")
     *
     * @SWG\Tag(name="Menu")
     * @SWG\Parameter(
     *     name="menu",
     *     in="body",
     *     type="object",
     *     description="Menu form",
     *     @Model(type=MenuType::class)
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Crate new menu",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Menu::class, groups={"read"})
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
        $form = $this->formFactory->submitRequest(MenuType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var MenuInterface $menu */
        $menu = $form->getData();
        $this->service->save($menu);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $request->getContent()));

        return $this->view($this->service->get($menu->getId()), Response::HTTP_CREATED);
    }
}
