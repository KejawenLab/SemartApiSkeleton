<?php

declare(strict_types=1);

namespace KejawenLab\Stub;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;

/**
 * @Permission(menu="TEST", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class StubController
{
    public function __invoke()
    {
    }
}
