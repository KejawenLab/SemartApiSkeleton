<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Message;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct(ApiClientInterface $apiClient, Request $request)
    {
        $this->apiClientId = $apiClient->getId();
        $this->path = $request->getPathInfo();
        $this->method = $request->getMethod();
        $this->headers = $request->headers->all();
        $this->queries = $request->query->all();
        $this->requests = $request->request->all();
        $this->files = $request->files->all();
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
