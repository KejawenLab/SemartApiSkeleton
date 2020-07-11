<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Authorization;

use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Model\UserRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Ownership
{
    private ManagerRegistry $doctrine;

    private UserRepositoryInterface $userRepository;

    private TokenStorageInterface $tokenStorage;

    private string $superAdmin;

    private string $ownershipProperty;

    public function __construct(
        ManagerRegistry $doctrine,
        UserRepositoryInterface $userRepository,
        TokenStorageInterface $tokenStorage,
        string $superAdmin,
        string $ownershipProperty
    ) {
        $this->doctrine = $doctrine;
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
        $this->superAdmin = $superAdmin;
        $this->ownershipProperty = $ownershipProperty;
    }

    public function isOwner(Request $request): bool
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return false;
        }

        /** @var UserInterface $user */
        $user = $token->getUser();
        if ($user->getGroup()->getCode() === $this->superAdmin) {
            return true;
        }

        if (!$class = $request->attributes->get('_api_resource_class')) {
            return false;
        }

        if (!$manager = $this->doctrine->getManagerForClass($class)) {
            return false;
        }

        if (!$object = $manager->getRepository($class)->find($request->get('id'))) {
            return false;
        }

        try {
            $propertyAccessor = new PropertyAccessor();
            $creator = $propertyAccessor->getValue($object, $this->ownershipProperty);
            $creatorUser = $this->userRepository->findByUsername($creator);
            if ($creatorUser && $this->userRepository->isSupervisor($creatorUser, $user)) {
                return true;
            }

            return $creator === $token->getUsername();
        } catch (\Exception $e) {
        }

        return false;
    }
}
