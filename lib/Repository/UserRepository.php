<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface as AppUser;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserRepository extends AbstractRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    private string $superAdmin;

    public function __construct(ManagerRegistry $registry, string $superAdmin)
    {
        $this->superAdmin = $superAdmin;

        parent::__construct($registry, User::class);
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function isSupervisor(AppUser $user, AppUser $supervisor): bool
    {
        if ($user->getSupervisor() && $user->getSupervisor()->getId() === $supervisor->getId()) {
            return true;
        }

        if (!$user->getSupervisor()) {
            if ($user->getGroup()->getCode() === $this->superAdmin) {
                return  true;
            }

            return false;
        }

        return $this->isSupervisor($user->getSupervisor(), $supervisor);
    }

    public function findByUsername(string $username): ?AppUser
    {
        return $this->findOneBy(['username' => $username]);
    }
}
