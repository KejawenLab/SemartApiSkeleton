<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminContext
{
    public const ADMIN_PATH_PREFIX = '/admin';

    public const LOGIN_ROUTE = 'admin_login';

    public const ADMIN_ROUTE = 'admin_home';

    public static function isAdminContext(Request $request): bool
    {
        if (static::ADMIN_PATH_PREFIX === substr($request->getPathInfo(), 0, strlen(static::ADMIN_PATH_PREFIX))) {
            return true;
        }

        return false;
    }
}
