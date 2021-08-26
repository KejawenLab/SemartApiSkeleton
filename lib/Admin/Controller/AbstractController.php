<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use InvalidArgumentException;
use KejawenLab\ApiSkeleton\Audit\Audit;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Base;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractController extends Base
{
    public function __construct(private ServiceInterface $service, private ?Paginator $paginator = null)
    {
    }

    protected function renderAudit(Audit $audit, ReflectionClass $class): Response
    {
        return $this->renderWithAudit($audit, $class, 'audit');
    }

    protected function renderDetail(Audit $audit, ReflectionClass $class): Response
    {
        return $this->renderWithAudit($audit, $class, 'view');
    }

    protected function renderList(FormInterface $form, Request $request, ReflectionClass $class): Response
    {
        if ($this->paginator === null) {
            throw new InvalidArgumentException(sprintf('%s is not passed', Paginator::class));
        }

        $context = StringUtil::lowercase($class->getShortName());

        return $this->render(sprintf('%s/all.html.twig', $context), [
            'page_title' => sprintf('sas.page.%s.list', $context),
            'context' => $context,
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, $class->getName()),
            'form' => $form->createView(),
        ]);
    }

    private function renderWithAudit(Audit $audit, ReflectionClass $class, string $template): Response
    {
        $context = StringUtil::lowercase($class->getShortName());
        $audits = $audit->toArray();

        return $this->render(sprintf('%s/%s.html.twig', $context, $template), [
            'page_title' => sprintf('sas.page.%s.view', $context),
            'context' => $context,
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $audits['entity'],
            'audits' => $audits['items'],
        ]);
    }
}
