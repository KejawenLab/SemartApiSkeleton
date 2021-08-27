<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupService extends AbstractService implements ServiceInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        GroupRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private string $superAdmin,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getSuperAdmin(): ?GroupInterface
    {
        return $this->repository->findByCode($this->superAdmin);
    }
}
