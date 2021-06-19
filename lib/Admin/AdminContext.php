<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AdminContext
{
    public const ADMIN_PATH_PREFIX = '/admin';

    public const LOGIN_ROUTE = 'admin_login';

    public const ADMIN_ROUTE = 'admin_home';

    public const USER_DEVICE_ID = 'USER_DEVICE_ID';

    public static function isAdminContext(Request $request): bool
    {
        if (str_starts_with($request->getPathInfo(), self::ADMIN_PATH_PREFIX)) {
            return true;
        }

        return false;
    }
}
