<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\UpdateProfileType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Security\User as AuthUser;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'PROFILE', actions: [Permission::EDIT])]
#[Tag(name: 'Profile')]
final class Put extends AbstractFOSRestController
{
    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly UserService $service,
        private readonly MediaService $mediaService,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route(data: '/me', name: self::class)]
    #[Security(name: 'Bearer')]
    #[RequestBody(
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(ref: new Model(type: UpdateProfileType::class), type: 'object'),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'User profile',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: User::class, groups: ['read']), type: 'object'),
        ),
    )]
    public function __invoke(Request $request): View
    {
        $user = $this->getUser();
        if (!$user instanceof AuthUser) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        /** @var User $user */
        $userClone = clone $user;
        $form = $this->formFactory->submitRequest(UpdateProfileType::class, $request, $user);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        if ($form['oldPassword']->getData() && $password = $form['newPassword']->getData()) {
            $user->setPlainPassword($password);
        }

        /** @var File $profile */
        $profile = $request->files->get('file');
        if ($profile) {
            $media = $this->mediaService->getByFile($user->getProfileImage());
            if (null !== $media) {
                $this->mediaService->remove($media);
            }

            $user->setFile($profile);
        } else {
            $user->setProfileImage($userClone->getProfileImage());
        }

        $this->service->save($user);

        return $this->view($this->service->get($user->getId()));
    }
}
