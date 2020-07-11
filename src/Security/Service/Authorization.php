<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Security\Annotation\Permission as Annotation;
use Alpabit\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Authorization
{
    private MenuRepositoryInterface $menuRepository;

    private AuthorizationCheckerInterface $checker;

    public function __construct(MenuRepositoryInterface $menuRepository, AuthorizationCheckerInterface $checker)
    {
        $this->menuRepository = $menuRepository;
        $this->checker = $checker;
    }

    public function authorize(Annotation $permission): bool
    {
        $menu = $this->menuRepository->findByCode($permission->getMenu());
        if (!$menu) {
            return false;
        }

        $actions = $permission->getActions();
        $count = count($actions);
        $granted = 0;
        foreach ($actions as $value) {
            if ($this->checker->isGranted($value, $menu)) {
                $granted++;
            }
        }

        if ($count === $granted) {
            return true;
        }

        return false;
    }
}
