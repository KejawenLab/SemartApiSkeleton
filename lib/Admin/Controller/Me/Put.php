<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Entity\User as RealUser;
use KejawenLab\ApiSkeleton\Form\UpdateProfileType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Put extends AbstractController
{
    public function __construct(private UserService $service, private MediaService $mediaService, private UserProviderFactory $userProviderFactory)
    {
    }

    /**
     * @Route("/me/edit", name=Put::class, methods={"POST"}, priority=-1)
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new RedirectResponse($this->generateUrl(AdminContext::ADMIN_ROUTE));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if (!$user instanceof UserInterface) {
            return new RedirectResponse($this->generateUrl(AdminContext::ADMIN_ROUTE));
        }

        /** @var RealUser $user */
        $media = $this->mediaService->getByFile($user->getProfileImage());
        if (null !== $media) {
            $this->mediaService->remove($media);
        }

        $form = $this->createForm(UpdateProfileType::class, $user);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form['oldPassword']->getData() && $password = $form['newPassword']->getData()) {
                    $user->setPlainPassword($password);
                }

                $this->service->save($user);
                $this->addFlash('info', 'sas.page.profile.updated');
            }
        }

        return new RedirectResponse($this->generateUrl(Profile::class));
    }
}
