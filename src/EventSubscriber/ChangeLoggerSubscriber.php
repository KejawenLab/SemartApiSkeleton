<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ChangeLoggerSubscriber implements EventSubscriber
{
    private const CREATE = 'CREATE';

    private const UPDATE = 'UPDATE';

    private const DELETE = 'DELETE';

    private $serializer;

    private $tokenStorage;

    private $logger;

    public function __construct(SerializerInterface $serializer, TokenStorageInterface $tokenStorage, LoggerInterface $dbLogger)
    {
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $dbLogger;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->log(static::CREATE, $args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->log(static::UPDATE, $args);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->log(static::DELETE, $args);
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    private function log(string $action, LifecycleEventArgs $event): void
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return;
        }

        $object = $event->getObject();
        if (static::UPDATE === $action) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $event->getObjectManager();
            $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($object);

            $this->logger->info(sprintf('[%s]<%s>[%s]', $token->getUsername(), $action, json_encode($changeSet)));
        } else {
            $this->logger->info(sprintf('[%s]<%s>[%s]', $token->getUsername(), $action, $this->serializer->serialize($object, 'json')));
        }
    }
}
