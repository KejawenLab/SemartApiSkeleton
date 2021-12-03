<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Authorization;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Ownership
{
    private const SKELETON_NAMESPACE = 'KejawenLab\\ApiSkeleton\\Entity';

    private const APPLICATION_NAMESPACE = 'KejawenLab\\Application\\Entity';

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly string $superAdmin,
        private readonly string $ownershipProperty,
    ) {
    }

    public function isOwner(string $id, string $entity): bool
    {
        if (($token = $this->tokenStorage->getToken()) === null) {
            return false;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if ($user->getGroup()->getCode() === $this->superAdmin) {
            return true;
        }

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$entity = $this->getEntity($entity)) {
            return false;
        }

        if (($manager = $this->doctrine->getManagerForClass($entity)) === null) {
            return false;
        }

        if (!$object = $manager->getRepository($entity)->find($id)) {
            return false;
        }

        try {
            $propertyAccessor = new PropertyAccessor();
            $creator = $propertyAccessor->getValue($object, $this->ownershipProperty);
            $creatorUser = $this->userRepository->findByUsername($creator);
            if (!$creatorUser instanceof UserInterface) {
                return $creator === $token->getUserIdentifier();
            }

            if (!$this->userRepository->isSupervisor($creatorUser, $user)) {
                return $creator === $token->getUserIdentifier();
            }

            return true;
        } catch (Exception) {
        }

        return false;
    }

    private function getEntity(string $entity): ?string
    {
        if (class_exists($entity = sprintf('%s\\%s', self::SKELETON_NAMESPACE, $entity))) {
            return $entity;
        }

        if (class_exists($entity = sprintf('%s\\%s', self::APPLICATION_NAMESPACE, $entity))) {
            return $entity;
        }

        return null;
    }
}
