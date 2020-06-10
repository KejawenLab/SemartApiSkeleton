<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Alpabit\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class UserService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, SettingRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getByDeviceId(string $deviceId): ?UserInterface
    {
        return $this->repository->findOneBy(['devideId' => $deviceId]);
    }
}
