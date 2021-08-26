<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\UpdateProfileType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Profile extends AbstractController
{
    public function __construct(
        private UserProviderFactory $userProviderFactory,
        private Paginator $paginator,
        private SettingService $setting,
        private ApiClientService $service,
    ) {
    }

    /**
     * @Route(path="/me", name=Profile::class, methods={"GET"}, priority=-1)
     *
     * @throws ReflectionException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new RedirectResponse($this->generateUrl(AdminContext::ADMIN_ROUTE));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        $class = new ReflectionClass($user::class);

        $request->query->set($this->setting->getPerPageField(), 17);

        return $this->render('profile/view.html.twig', [
            'page_title' => 'sas.page.profile.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'api_clients' => (new ReflectionClass(ApiClient::class))->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClient::class),
            'data' => $user,
            'form' => $this->createForm(UpdateProfileType::class, $user)->createView(),
        ]);
    }
}
