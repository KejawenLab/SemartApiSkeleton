<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Audit\Audit as Record;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private SettingService $service, private AuditService $audit, private Reader $reader)
    {
        parent::__construct($this->service);
    }

    #[Route(path: '/settings/{id}', name: Get::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }
        $audit = new Record($setting);
        if ($this->reader->getProvider()->isAuditable(Setting::class)) {
            $audit = $this->audit->getAudits($setting, $id, 1);
        }

        return $this->renderDetail($audit, new ReflectionClass(Setting::class));
    }
}
