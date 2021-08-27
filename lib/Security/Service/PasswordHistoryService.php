<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordHistoryService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, PasswordHistoryRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @return PasswordHistoryInterface[]
     */
    public function getPasswords(UserInterface $user): iterable
    {
        return $this->repository->findPasswords($user);
    }
}
