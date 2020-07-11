<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\User;

use Alpabit\ApiSkeleton\Audit\AuditService;
use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\UserService;
use DH\DoctrineAuditBundle\Reader\AuditReader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Audit extends AbstractFOSRestController
{
    private UserService $service;

    private AuditService $audit;

    private AuditReader $reader;

    public function __construct(UserService $service, AuditService $audit, AuditReader $reader)
    {
        $this->service = $service;
        $this->audit = $audit;
        $this->reader = $reader;
    }

    /**
     * @Rest\Get("/users/{id}/audit")
     *
     * @Cache(expires="+17 minute", public=false)
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Return audit list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *             @SWG\Property(type="object", property="entity", ref=@Model(type=User::class, groups={"read"})),
     *             @SWG\Property(type="array", property="items", @SWG\Items(
     *                 @SWG\Property(type="string", property="type"),
     *                 @SWG\Property(type="string", property="user_id"),
     *                 @SWG\Property(type="string", property="username"),
     *                 @SWG\Property(type="string", property="ip_address"),
     *                 @SWG\Property(type="array", property="data", @SWG\Items(
     *                     @SWG\Property(type="array", property="field1", @SWG\Items(
     *                         @SWG\Property(type="string", property="new"),
     *                         @SWG\Property(type="string", property="old"),
     *                     )),
     *                     @SWG\Property(type="array", property="field2", @SWG\Items(
     *                         @SWG\Property(type="string", property="new"),
     *                         @SWG\Property(type="string", property="old"),
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
     *
     * @param Request $request
     *
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getConfiguration()->isAuditable(User::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
