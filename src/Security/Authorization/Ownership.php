<?php

declare(strict_types=1);

namespace App\Security\Authorization;

use App\Security\Model\UserInterface;
use App\Security\Model\UserRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Ownership
{
    private $doctrine;

    private $userRepository;

    private $token;

    private $superAdmin;

    private $ownershipProperty;

    public function __construct(
        ManagerRegistry $doctrine,
        UserRepositoryInterface $userRepository,
        TokenStorageInterface $tokenStorage,
        string $superAdmin,
        string $ownershipProperty
    ) {
        $this->doctrine = $doctrine;
        $this->userRepository = $userRepository;
        $this->token = $tokenStorage->getToken();
        $this->superAdmin = $superAdmin;
        $this->ownershipProperty = $ownershipProperty;
    }

    public function isOwner(Request $request): bool
    {
        if (!$this->token) {
            return false;
        }

        /** @var UserInterface $user */
        $user = $this->token->getUser();
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

            return $creator === $this->token->getUsername();
        } catch (\Exception $e) {
        }

        return false;
    }
}
