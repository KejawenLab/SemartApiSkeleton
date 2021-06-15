<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\UpdateProfileType;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Put extends AbstractController
{
    public function __construct(private FormFactory $formFactory, private UserService $service)
    {
    }

    /**
     * @Route("/me/edit", methods={"GET", "POST"}, priority=-1)
     */
    public function __invoke(Request $request, UserProviderFactory $userProviderFactory): Response
    {
        $user = $userProviderFactory->getRealUser($this->getUser());
        $form = $this->createForm(UpdateProfileType::class, $user);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form['oldPassword']->getData() && $password = $form['newPassword']->getData()) {
                    $user->setPlainPassword($password);
                }

                $this->service->save($user);

                $this->addFlash('info', 'sas.page.profile.updated');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_me_profile__invoke'));
            }
        }

        return $this->render('profile/update.html.twig', [
            'page_title' => 'sas.page.profile.update',
            'form' => $form->createView(),
        ]);
    }
}
