<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AdminContext
{
    final public const ADMIN_PATH_PREFIX = '/admin';

    final public const LOGIN_ROUTE = 'admin_login';

    final public const ADMIN_ROUTE = 'admin_home';

    final public const USER_DEVICE_ID = 'USER_DEVICE_ID';

    public static function isAdminContext(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), self::ADMIN_PATH_PREFIX);
    }
}
