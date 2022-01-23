<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface SettingInterface extends EntityInterface
{
    final public const CACHE_ID_CACHE_LIFETIME = 'de35124f15a4ef9f5a50ce1dd9027556e12a221d';

    final public const CACHE_ID_PAGE_FIELD = 'd5c8846a9629410a4930ecbe67e075dfa02020ea';

    final public const CACHE_ID_PER_PAGE_FIELD = '964c2f9a9b4747561a06c6064f3ccd874fea04ab';

    final public const CACHE_ID_PER_PAGE = '872bea19cf26dc5b03267f3327e888bf0993d702';

    final public const CACHE_ID_MAX_API_PER_USER = '95afbfd9c39da2360855134e2bc998deb1bc4c20';

    public function getGroup(): ?string;

    public function getParameter(): ?string;

    public function getValue(): ?string;

    public function isPublic(): bool;

    public function isReserved(): bool;
}
