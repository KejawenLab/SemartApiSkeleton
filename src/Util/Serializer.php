<?php

declare(strict_types=1);

namespace App\Util;

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

    public function serialize($data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function toArray($data, array $context = []): array
    {
        return json_decode($this->serialize($data, 'json', $context), true);
    }
}
