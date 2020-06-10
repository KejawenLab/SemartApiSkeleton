<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Messenger;

use Alpabit\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class EntityMessageHandler implements MessageHandlerInterface
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(EntityMessage $message): void
    {
        $class = get_class($message->getData());
        $manager = $this->registry->getManagerForClass($class);
        if (!$manager) {
            throw new EntityNotFoundException();
        }

        /** @var ServiceableRepositoryInterface $repository */
        $repository = $manager->getRepository($class);
        if (EntityMessage::ACTION_PERSIST === $message->getAction()) {
            $repository->persist($message->getData());
        }

        if (EntityMessage::ACTION_REMOVE === $message->getAction()) {
            $repository->remove($message->getData());
        }
    }
}
