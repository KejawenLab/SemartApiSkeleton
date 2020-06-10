<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Menu;

use Alpabit\ApiSkeleton\Entity\Menu;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\MenuType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="MENU", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Put extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private MenuService $service;

    private LoggerInterface $logger;

    public function __construct(FormFactory $formFactory, MenuService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Put("/menus/{id}")
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
     *     response=200,
     *     description="Update menu",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Menu::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            throw new NotFoundHttpException(sprintf('Menu with ID "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(MenuType::class, $request, $menu);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($menu);

        $this->logger->info(sprintf('[%s][%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id, $request->getContent()));

        return $this->view($this->service->get($menu->getId()));
    }
}
