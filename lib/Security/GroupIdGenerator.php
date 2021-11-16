<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use Ramsey\Uuid\Uuid;

final class GroupIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        if ($entity instanceof GroupInterface && GroupInterface::SUPER_ADMIN_CODE === $entity->getCode()) {
            return Uuid::fromString(GroupInterface::SUPER_ADMIN_ID);
        }

        return Uuid::uuid4();
    }
}
