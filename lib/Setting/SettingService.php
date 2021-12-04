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
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SettingService extends AbstractService implements ServiceInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        SettingRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private readonly SettingGroupFactory $groupFactory,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function getSetting(string $key): SettingInterface
    {
        if ($setting = $this->repository->findByParameter($key)) {
            return $setting;
        }

        throw new SettingNotFoundException();
    }

    /**
     * @return array<string>
     */
    public function getGroups(): array
    {
        return $this->groupFactory->getGroups();
    }

    public function getPublicSetting(string $id): ?SettingInterface
    {
        return $this->repository->findPublicSetting($id);
    }

    public function getCacheLifetime(): int
    {
        return (int) $this->getSetting('CACHE_LIFETIME')->getValue();
    }

    public function getPageField(): ?string
    {
        return $this->getSetting('PAGE_FIELD')->getValue();
    }

    public function getPerPageField(): ?string
    {
        return $this->getSetting('PER_PAGE_FIELD')->getValue();
    }

    public function getRecordPerPage(): int
    {
        return (int) $this->getSetting('PER_PAGE')->getValue();
    }

    public function getMaxApiPerUser(): int
    {
        return (int) $this->getSetting('MAX_API_PER_USER')->getValue();
    }
}
