<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\UserType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Main extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UserService $service,
        private readonly CacheItemPoolInterface $cache,
        private readonly Paginator $paginator,
    ) {
        parent::__construct($this->requestStack->getCurrentRequest(), $this->service, $this->cache, $this->paginator);
    }

    #[Route(path: '/users', name: Main::class, methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $user = new User();
        if ($request->isMethod(Request::METHOD_POST)) {
            $id = $request->getSession()->get('id');
            if (null !== $id) {
                $user = $this->service->get($id);
            }
        } else {
            $flashes = $request->getSession()->getFlashBag()->get('id');
            foreach ($flashes as $flash) {
                $user = $this->service->get($flash);
                if (null !== $user) {
                    $request->getSession()->set('id', $user->getId());

                    break;
                }
            }
        }

        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($form->getData());
                $this->addFlash('info', 'sas.page.user.saved');

                $form = $this->createForm(UserType::class);
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(User::class));
    }
}
