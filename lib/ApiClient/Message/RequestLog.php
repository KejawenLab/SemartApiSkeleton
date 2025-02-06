<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Message;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Attribute\AsMessage;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsMessage('sync')]
final class RequestLog
{
    private readonly string $apiClientId;

    private readonly string $path;

    private readonly string $method;

    private readonly array $headers;

    private readonly array $queries;

    private readonly array $requests;

    private readonly array $files;

    private readonly string $content;

    private readonly int $statusCode;

    public function __construct(ApiClientInterface $apiClient, Request $request, Response $response)
    {
        $this->apiClientId = $apiClient->getId();
        $this->path = $request->getPathInfo();
        $this->method = $request->getMethod();
        $this->headers = $request->headers->all();
        $this->queries = $request->query->all();
        $this->requests = $request->request->all();
        $this->files = $request->files->all();
        $this->content = (string)$response->getContent();
        $this->statusCode = $response->getStatusCode();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getApiClientId(): string
    {
        return $this->apiClientId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return mixed[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @return mixed[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
