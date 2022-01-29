<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient;

use Iterator;
use KejawenLab\ApiSkeleton\ApiClient\Message\RequestLog;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ApiClientRequestService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        ApiClientRequestRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private readonly ApiClientRepositoryInterface $apiClientRepository,
        private readonly string $class,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(RequestLog $message): void
    {
        /** @var ApiClientInterface $apiClient */
        $apiClient = $this->apiClientRepository->find($message->getApiClientId());
        if (null === $apiClient) {
            return;
        }

        $apiClientRequest = $this->createFromMessage($message);
        $apiClientRequest->setApiClient($apiClient);

        $this->save($apiClientRequest);
    }

    /**
     * @return Iterator<string>
     */
    public static function getHandledMessages(): iterable
    {
        yield RequestLog::class;
    }

    private function createFromMessage(RequestLog $message): ApiClientRequestInterface
    {
        /** @var ApiClientRequestInterface $apiClientRequest */
        $apiClientRequest = new $this->class();
        $apiClientRequest->setPath($message->getPath());
        $apiClientRequest->setMethod($message->getMethod());
        $apiClientRequest->setHeaders($message->getHeaders());
        $apiClientRequest->setQueries($message->getQueries());
        $apiClientRequest->setRequests($message->getRequests());
        $apiClientRequest->setFiles($message->getFiles());
        $apiClientRequest->setContent($message->getContent());
        $apiClientRequest->setStatusCode($message->getStatusCode());

        return $apiClientRequest;
    }
}
