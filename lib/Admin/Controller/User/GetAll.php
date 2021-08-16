<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\UserType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GetAll extends AbstractController
{
    public function __construct(private UserService $service, private Paginator $paginator)
    {
    }

    /**
     * @Route("/users", name=GetAll::class, methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new ReflectionClass(User::class);
        $user = new User();
        $flashs = $request->getSession()->getFlashBag()->get('id');
        foreach ($flashs as $flash) {
            $user = $this->service->get($flash);
            if ($user) {
                $this->addFlash('id', $user->getId());

                break;
            }
        }

        return $this->render('user/all.html.twig', [
            'page_title' => 'sas.page.user.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, User::class),
            'form' => $this->createForm(UserType::class, $user)->createView(),
        ]);
    }
}
