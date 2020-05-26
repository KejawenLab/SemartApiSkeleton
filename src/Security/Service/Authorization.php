<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\Annotation\Permission as Annotation;
use App\Security\Model\MenuRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Authorization
{
    private $menuRepository;

    private $checker;

    public function __construct(MenuRepositoryInterface $menuRepository, AuthorizationCheckerInterface $checker)
    {
        $this->menuRepository = $menuRepository;
        $this->checker = $checker;
    }

    public function authorize(Annotation $permission): bool
    {
        $menu = $this->menuRepository->findByCode($permission->getMenu());
        foreach ($permission->getActions() as $value) {
            if ($this->checker->isGranted($menu, $value)) {
                return true;
            }
        }

        return false;
    }
}
