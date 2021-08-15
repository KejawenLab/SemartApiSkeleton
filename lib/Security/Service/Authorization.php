<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission as Annotation;
use KejawenLab\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Authorization
{
    public function __construct(private MenuRepositoryInterface $menuRepository, private AuthorizationCheckerInterface $checker)
    {
    }

    public function authorize(Annotation $permission): bool
    {
        $menu = $this->menuRepository->findByCode($permission->getMenu());
        if (!$menu) {
            return false;
        }

        $actions = $permission->getActions();
        foreach ($actions as $value) {
            if (!$this->checker->isGranted($value, $menu)) {
                return false;
            }
        }

        return true;
    }
}
