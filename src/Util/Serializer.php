<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Util;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Serializer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, string $format): string
    {
        return $this->serializer->serialize($data, $format, ['groups' => 'read']);
    }

    public function toArray($data): array
    {
        return json_decode($this->serialize($data, 'json'), true);
    }
}
