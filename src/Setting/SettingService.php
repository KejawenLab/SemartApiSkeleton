<?php

declare(strict_types=1);

namespace App\Setting;

use App\Pagination\AliasHelper;
use App\Service\AbstractService;
use App\Service\Model\ServiceInterface;
use App\Setting\Model\SettingInterface;
use App\Setting\Model\SettingRepositoryInterface;
use App\Util\Serializer;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SettingService extends AbstractService implements ServiceInterface
{
    public function __construct(SettingRepositoryInterface $repository, Serializer $serializer, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $serializer, $aliasHelper);
    }

    public function getSetting(string $key): SettingInterface
    {
        if ($setting = $this->repository->findByParameter($key)) {
            return $setting;
        }

        throw new SettingNotFoundException();
    }

    public function support(object $object): bool
    {
        return $object instanceof SettingInterface;
    }
}
