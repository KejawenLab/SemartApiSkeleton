<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use ReflectionClass;
use ReflectionProperty;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Profile extends AbstractController
{
    /**
     * @Route("/me", methods={"GET"}, priority=-1)
     */
    public function __invoke(UserProviderFactory $userProviderFactory): Response
    {
        $user = $userProviderFactory->getRealUser($this->getUser());
        $class = new ReflectionClass($user::class);

        return $this->render('profile/view.html.twig', [
            'page_title' => 'sas.page.profile.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $user,
        ]);
    }
}
