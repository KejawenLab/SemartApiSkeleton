<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit extends AbstractController
{
    public function __construct(private ApiClientService $service, private AuditService $audit, private Reader $reader)
    {
    }

    /**
     * @Route("/api-clients/{id}/audit", name=Audit::class, methods={"GET"}, priority=-255)
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(string $id): Response
    {
        if (!$entity = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.api_client.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        if (!$this->reader->getProvider()->isAuditable(Group::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        $class = new ReflectionClass(ApiClient::class);
        $audit = $this->audit->getAudits($entity, $id)->toArray();

        return $this->render('api_client/audit.html.twig', [
            'page_title' => 'sas.page.audit.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $audit['entity'],
            'audits' => $audit['items'],
        ]);
    }
}
