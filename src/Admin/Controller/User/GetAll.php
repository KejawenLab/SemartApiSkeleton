<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractController
{
    private UserService $service;

    private Paginator $paginator;

    public function __construct(UserService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new \ReflectionClass(User::class);

        return $this->render('user/all.html.twig', [
            'page_title' => 'sas.page.user.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, User::class),
        ]);
    }
}
