<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Download extends AbstractController
{
    private UserService $service;

    private SerializerInterface $serializer;

    public function __construct(UserService $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/users/download", methods={"GET"})
     */
    public function __invoke(): Response
    {
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s_%s.csv"', 'users', date('YmdHis')));

        $response->setContent($this->serializer->serialize($this->service->all(), 'csv', ['groups' => 'read']));

        return $response;
    }
}
