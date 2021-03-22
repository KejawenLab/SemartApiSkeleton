<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Audit extends AbstractFOSRestController
{
    private SettingService $service;

    private AuditService $audit;

    private Reader $reader;

    public function __construct(SettingService $service, AuditService $audit, Reader $reader)
    {
        $this->service = $service;
        $this->audit = $audit;
        $this->reader = $reader;
    }

    /**
     * @Rest\Get("/settings/{id}/audit", priority=-255)
     *
     * @Cache(expires="+17 minute", public=false)
     *
     * @OA\Tag(name="Setting")
     * @OA\Response(
     *     response=200,
     *     description="Return audit list",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             @OA\Property(type="object", property="entity", ref=@Model(type=Setting::class, groups={"read"})),
     *             @OA\Property(type="array", property="items", @OA\Items(
     *                 @OA\Property(type="string", property="type"),
     *                 @OA\Property(type="string", property="user_id"),
     *                 @OA\Property(type="string", property="username"),
     *                 @OA\Property(type="string", property="ip_address"),
     *                 @OA\Property(type="array", property="data", @OA\Items(
     *                     @OA\Property(type="array", property="field1", @OA\Items(
     *                         @OA\Property(type="string", property="new"),
     *                         @OA\Property(type="string", property="old"),
     *                     )),
     *                     @OA\Property(type="array", property="field2", @OA\Items(
     *                         @OA\Property(type="string", property="new"),
     *                         @OA\Property(type="string", property="old"),
     *                     ))
     *                 ))
     *             ))
     *         )
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, string $id): View
    {
        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(Setting::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
