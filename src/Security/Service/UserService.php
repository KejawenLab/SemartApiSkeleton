<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, UserRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getByDeviceId(string $deviceId): ?UserInterface
    {
        return $this->repository->findOneBy(['deviceId' => $deviceId]);
    }
}
