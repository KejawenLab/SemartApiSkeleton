<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ApiClientRequestService extends AbstractService implements ServiceInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        ApiClientRequestRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private string $class,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function createFromRequest(Request $request): ApiClientRequestInterface
    {
        /** @var ApiClientRequestInterface $apiClientRequest */
        $apiClientRequest = new $this->class();
        $apiClientRequest->setPath($request->getPathInfo());
        $apiClientRequest->setMethod($request->getMethod());
        $apiClientRequest->setHeaders($request->headers->all());
        $apiClientRequest->setQueries($request->query->all());
        $apiClientRequest->setRequests($request->request->all());
        $apiClientRequest->setFiles($request->files->all());

        return $apiClientRequest;
    }
}
