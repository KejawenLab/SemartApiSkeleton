<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use Alpabit\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PasswordHistoryService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, PasswordHistoryRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @param UserInterface $user
     *
     * @return PasswordHistoryInterface[]
     */
    public function getPasswords(UserInterface $user): array
    {
        return $this->repository->findPassword($user);
    }
}
