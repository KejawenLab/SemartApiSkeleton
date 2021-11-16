<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Message;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class RequestLog
{
    private string $apiClientId;

    private string $path;

    private string $method;

    private array $headers;

    private array $queries;

    private array $requests;

    private array $files;

    private string $content;

    private int $statusCode;

    public function __construct(ApiClientInterface $apiClient, Request $request, Response $response)
    {
        $this->apiClientId = $apiClient->getId();
        $this->path = $request->getPathInfo();
        $this->method = $request->getMethod();
        $this->headers = $request->headers->all();
        $this->queries = $request->query->all();
        $this->requests = $request->request->all();
        $this->files = $request->files->all();
        $this->content = (string) $response->getContent();
        $this->statusCode = $response->getStatusCode();
    }

    public function getApiClientId(): string
    {
        return $this->apiClientId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getRequests(): array
    {
        return $this->requests;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
