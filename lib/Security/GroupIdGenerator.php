<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity): UuidInterface
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
