<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Setting;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Util\Serializer;

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
}
