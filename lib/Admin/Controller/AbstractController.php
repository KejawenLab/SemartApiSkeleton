<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use InvalidArgumentException;
use KejawenLab\ApiSkeleton\Audit\Audit;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Util\CacheFactory;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use SebastianBergmann\ObjectReflector\ObjectReflector;
use SebastianBergmann\RecursionContext\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Base;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractController extends Base
{
    public function __construct(
        private readonly Request $request,
        private readonly ServiceInterface $service,
        private readonly CacheFactory $cache,
        private readonly ?Paginator $paginator = null,
    ) {
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        if ($this->cache->isDisableViewCache()) {
            return parent::renderView($view, $parameters);
        }

        $params = [];
        foreach ($parameters as $parameter) {
            if ($this->canBeSerialized($parameter)) {
                $params[] = $parameter;
            }
        }

        $params = array_merge($params, $this->request->getSession()->all());
        $params = array_merge($params, $this->request->query->all());

        $key = sprintf('%s_%s', sha1($view), sha1(serialize($params)));
        $data = $this->cache->getCache($key, 'view');
        if (0 !== count($data)) {
            return $data['content'];
        }

        $content = parent::renderView($view, $parameters);
        $this->cache->setCache($key, 'view', $content, true, SemartApiSkeleton::VIEW_CACHE_PERIOD);

        return $content;
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
        if (null === $this->paginator) {
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

    private function canBeSerialized($variable): bool
    {
        if (is_scalar($variable)) {
            return true;
        }

        if ($variable === null) {
            return true;
        }

        if (is_resource($variable)) {
            return false;
        }

        foreach ($this->enumerateObjectsAndResources($variable) as $value) {
            if (is_resource($value)) {
                return false;
            }

            if (is_object($value)) {
                $class = new ReflectionClass($value);
                if ($class->isAnonymous()) {
                    return false;
                }

                try {
                    serialize($value);
                } catch (Throwable) {
                    return false;
                }
            }
        }

        return true;
    }

    private function enumerateObjectsAndResources($variable): array
    {
        $processed = func_get_args()[1] ?? new Context();
        $result = [];
        if ($processed->contains($variable)) {
            return $result;
        }

        $array = $variable;
        $processed->add($variable);
        if (is_array($variable)) {
            foreach ($array as $element) {
                if (!is_array($element) && !is_object($element) && !is_resource($element)) {
                    continue;
                }

                if (!is_resource($element)) {
                    $result = array_merge(
                        $result,
                        $this->enumerateObjectsAndResources($element, $processed)
                    );
                } else {
                    $result[] = $element;
                }
            }
        } else {
            $result[] = $variable;
            foreach ((new ObjectReflector())->getAttributes($variable) as $value) {
                if (!is_array($value) && !is_object($value) && !is_resource($value)) {
                    continue;
                }

                if (!is_resource($value)) {
                    $result = array_merge(
                        $result,
                        $this->enumerateObjectsAndResources($value, $processed)
                    );
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
