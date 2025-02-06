<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Entity\PasswordHistory as Entity;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Message\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsMessageHandler]
final class PasswordHistoryService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, PasswordHistoryRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(PasswordHistory $message): void
    {
        $entity = new Entity();
        $entity->setSource($message->getSource());
        $entity->setIdentifier($message->getIdentifier());
        $entity->setPassword($message->getPassword());

        $this->save($entity);
    }

    /**
     * @return PasswordHistoryInterface[]
     */
    public function getPasswords(UserInterface $user): iterable
    {
        return $this->repository->findPasswords($user);
    }
}
