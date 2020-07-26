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

    public const ADMIN_CRSF_SESSION_KEY = '__sas__admin__crsf__';

    public const ADMIN_CRSF_REQUEST_KEY = '__crsf_token__';

    public const ADMIN_ACTION_KEY = 'action';

    public const ADMIN_LOGIN_ACTION = 'login';

    public static function isAdminContext(Request $request): bool
    {
        if (static::ADMIN_PATH_PREFIX === substr($request->getPathInfo(), 0, strlen(static::ADMIN_PATH_PREFIX))) {
            return true;
        }

        return false;
    }
}
