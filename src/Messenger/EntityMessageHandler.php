<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Messenger;

use Alpabit\ApiSkeleton\Entity\Event\PersistEvent;
use Alpabit\ApiSkeleton\Entity\Event\RemoveEvent;
use Alpabit\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class EntityMessageHandler implements MessageHandlerInterface
{
    private $registry;

    private $dispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->registry = $registry;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(EntityMessage $message): void
    {
        $class = get_class($message->getData());
        /** @var EntityManagerInterface $manager */
        $manager = $this->registry->getManagerForClass($class);
        if (!$manager) {
            throw new EntityNotFoundException();
        }

        /** @var ServiceableRepositoryInterface $repository */
        $repository = $manager->getRepository($class);
        if (EntityMessage::ACTION_PERSIST === $message->getAction()) {
            $this->dispatcher->dispatch(new PersistEvent($manager, $message->getData()));
            $repository->persist($message->getData());
        }

        if (EntityMessage::ACTION_REMOVE === $message->getAction()) {
            $this->dispatcher->dispatch(new RemoveEvent($manager, $message->getData()));
            $repository->remove($message->getData());
        }
    }
}
