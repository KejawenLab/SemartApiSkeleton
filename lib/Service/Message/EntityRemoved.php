<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsMessage('async')]
final class EntityRemoved extends EntityPersisted
{
}
