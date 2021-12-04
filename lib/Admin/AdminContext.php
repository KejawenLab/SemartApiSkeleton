<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AdminContext
{
    public final const ADMIN_PATH_PREFIX = '/admin';

    public final const LOGIN_ROUTE = 'admin_login';

    public final const ADMIN_ROUTE = 'admin_home';

    public final const USER_DEVICE_ID = 'USER_DEVICE_ID';

    public static function isAdminContext(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), self::ADMIN_PATH_PREFIX);
    }
}
