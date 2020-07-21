<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Twig;

use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MenuExtension extends AbstractExtension
{
    private Environment $twig;

    private UrlGeneratorInterface $urlGenerator;

    private MenuService $menuService;

    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator, MenuService $menuService)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->menuService = $menuService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('to_menu', [$this, 'getMenu']),
            new TwigFunction('render_menu', [$this, 'renderMenu']),
        ];
    }

    public function getMenu(string $code): ?MenuInterface
    {
        return $this->menuService->getMenuByCode($code);
    }

    public function renderMenu(string $menuCode): string
    {
        $html = '';
        /** @var MenuInterface[] $parentMenus */
        $parentMenus = $this->menuService->getParentMenu();
        foreach ($parentMenus as $menu) {
            if (!$this->menuService->hasChildMenu($menu)) {
                $html = sprintf('%s%s', $html, $this->buildHtml($menu, $menuCode));

                continue;
            }

            $childs = $this->menuService->getChildsMenu($menu);
            $html = sprintf('%s%s', $html, $this->twig->render('layout/menu.html.twig', ['childs' => $childs, 'menu_code' => $menuCode]));
        }

        return $html;
    }

    private function buildHtml(MenuInterface $menu, string $menuCode): string
    {
        $url = $menu->getRouteName() ? $this->urlGenerator->generate($menu->getRouteName()) : '#';
        $active = $menu->getCode() === $menuCode ? 'active' : '';

        return $this->twig->render('layout/child_menu.html.twig', [
            'menu' => $menu,
            'url' => $url,
            'active' => $active,
        ]);
    }
}
