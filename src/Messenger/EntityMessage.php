<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Messenger;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class EntityMessage
{
    public const ACTION_PERSIST = 'persist';

    public const ACTION_REMOVE = 'remove';

    private $action;

    private $data;

    public function __construct(object $data, string $action = self::ACTION_PERSIST)
    {
        $this->data = $data;
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getData(): object
    {
        return $this->data;
    }
}
