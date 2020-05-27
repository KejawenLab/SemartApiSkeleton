<?php

declare(strict_types=1);

namespace App\Setting;

use App\Pagination\Pagination;
use App\Setting\Model\SettingInterface;
use App\Setting\Model\SettingRepositoryInterface;
use App\Util\Serializer;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SettingService
{
    private $repository;

    private $serializer;

    public function __construct(SettingRepositoryInterface $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    public function getSetting(string $key): SettingInterface
    {
        if ($setting = $this->repository->findByParameter($key)) {
            return $setting;
        }

        throw new SettingNotFoundException();
    }

    public function get(string $id, bool $toArray = false)
    {
        if ($toArray) {
            return $this->serializer->toArray($this->repository->find($id), ['groups' => 'read']);
        }

        return $this->repository->find($id);
    }

    public function save(SettingInterface $setting): void
    {
        $this->repository->persist($setting);
    }

    public function remove(SettingInterface $setting): void
    {
        $this->repository->remove($setting);
    }

    public function paginate(Pagination $pagination, array $filters = []): array
    {
        return [
            'items' => $this->serializer->toArray($this->repository->paginate($pagination, $filters), ['groups' => 'read']),
            'page' => $pagination->getPage(),
            'per_page' => $pagination->getPerPage(),
            'total' => $this->repository->count($filters),
            'total_page' => ceil($this->repository->count($filters) / $pagination->getPerPage()),
        ];
    }
}
