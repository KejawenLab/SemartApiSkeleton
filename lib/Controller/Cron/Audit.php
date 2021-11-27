<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit extends AbstractFOSRestController
{
    public function __construct(private CronService $service, private AuditService $audit, private Reader $reader)
    {
    }

    /**
     * @Cache(expires="+17 minute", public=false)
     *
     * @OA\Tag(name="Cron")
     * @OA\Response(
     *     response=200,
     *     description= "Audit list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     properties={
     *                         @OA\Property(
     *                             property="entity",
     *                             type="object",
     *                             @OA\Schema(
     *                                 type="object",
     *                                 ref=@Model(type=Cron::class, groups={"read"})
     *                             )
     *                         ),
     *                         @OA\Property(type="string", property="type"),
     *                         @OA\Property(type="string", property="user_id"),
     *                         @OA\Property(type="string", property="username"),
     *                         @OA\Property(type="string", property="ip_address"),
     *                         @OA\Property(
     *                             type="array",
     *                             property="data",
     *                             @OA\Items(
     *                                 @OA\Property(type="string", property="new"),
     *                                 @OA\Property(type="string", property="old"),
     *                             )
     *                         )
     *                     }
     *                 )
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Get(data: '/cronjobs/{id}/audit', name: Audit::class, priority: -255)]
    public function __invoke(string $id): View
    {
        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(Cron::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
