<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\ArgumentResolver;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class BaseApiUrlResolver implements ArgumentValueResolverInterface
{
    private string $baseApiUrl;

    public function __construct(string $baseApiUrl)
    {
        $this->baseApiUrl = $baseApiUrl;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (AdminContext::isAdminContext($request) && 'string' === $argument->getType() && 'baseApiUrl' === $argument->getName()) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->baseApiUrl;
    }
}
