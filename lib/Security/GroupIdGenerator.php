<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupIdGenerator extends AbstractIdGenerator
{
    public function generateId(EntityManagerInterface $em, object|null $entity): mixed
    {
        if (!$entity instanceof GroupInterface) {
            return Uuid::uuid4();
        }

        if (GroupInterface::SUPER_ADMIN_CODE !== $entity->getCode()) {
            return Uuid::uuid4();
        }

        return Uuid::fromString(GroupInterface::SUPER_ADMIN_ID);
    }
}
