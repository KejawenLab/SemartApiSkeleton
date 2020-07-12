<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SettingService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, SettingRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getSetting(string $key): SettingInterface
    {
        if ($setting = $this->repository->findByParameter($key)) {
            return $setting;
        }

        throw new SettingNotFoundException();
    }

    public function getPublicSetting(string $id): ?SettingInterface
    {
        return $this->repository->findOneBy(['id' => $id, 'public' => true]);
    }
}
