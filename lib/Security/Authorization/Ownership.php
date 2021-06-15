<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Authorization;

use Exception;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Ownership
{
    private const NAMESPACE = 'KejawenLab\ApiSkeleton\Entity';

    public function __construct(private ManagerRegistry $doctrine, private UserRepositoryInterface $userRepository, private TokenStorageInterface $tokenStorage, private UserProviderFactory $userProviderFactory, private string $superAdmin, private string $ownershipProperty)
    {
    }

    public function isOwner(string $id, string $entity): bool
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return false;
        }

        /** @var UserInterface $user */
        $user = $this->userProviderFactory->getRealUser($token->getUser());
        if ($user->getGroup()->getCode() === $this->superAdmin) {
            return true;
        }

        if (!class_exists($entity = sprintf('%s\%s', static::NAMESPACE, $entity))) {
            return false;
        }

        if (!$manager = $this->doctrine->getManagerForClass($entity)) {
            return false;
        }

        if (!$object = $manager->getRepository($entity)->find($id)) {
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
        } catch (Exception) {
        }

        return false;
    }
}
