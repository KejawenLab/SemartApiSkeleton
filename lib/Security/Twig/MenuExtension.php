<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Twig;

use Iterator;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuExtension extends AbstractExtension
{
    public function __construct(
        private readonly Environment  $twig,
        private readonly RequestStack $requestStack,
        private readonly MenuService  $menuService,
    )
    {
    }

    /**
     * @return Iterator<TwigFunction>
     */
    #[\Override]
    public function getFunctions(): iterable
    {
        yield new TwigFunction('convert_to_menu', [$this, 'getMenu']);
        yield new TwigFunction('render_menu', [$this, 'renderMenu']);
        yield new TwigFunction('is_active_path', [$this, 'isActive']);
        yield new TwigFunction('is_menu_open', [$this, 'isOpen']);
    }

    public function isOpen(MenuInterface $menu): bool
    {
        if (!$this->menuService->hasChildMenu($menu)) {
            return $this->isActive($menu->getAdminPath());
        }

        $childs = $this->menuService->getChildsMenu($menu);
        foreach ($childs as $child) {
            if ($this->isActive($child->getAdminPath())) {
                return true;
            }
        }

        return false;
    }

    public function isActive(string $path): bool
    {
        return $this->requestStack->getCurrentRequest()->getPathInfo() === $path;
    }

    public function getMenu(string $code): ?MenuInterface
    {
        return $this->menuService->getMenuByCode($code);
    }

    public function renderMenu(): string
    {
        $html = '';
        /** @var MenuInterface[] $parentMenus */
        $parentMenus = $this->menuService->getParentMenu();
        foreach ($parentMenus as $menu) {
            if (!$this->menuService->hasChildMenu($menu)) {
                $html = sprintf('%s%s', $html, $this->twig->render('layout/child_menu.html.twig', ['menu' => $menu]));

                continue;
            }

            $childs = iterator_to_array($this->menuService->getChildsMenu($menu));
            $html = sprintf('%s%s', $html, $this->twig->render('layout/menu.html.twig', ['childs' => $childs, 'menu' => $menu]));
        }

        return $html;
    }
}
