<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface ApiClientRequestInterface extends EntityInterface
{
    public function getApiClient(): ?ApiClientInterface;

    public function setApiClient(ApiClientInterface $apiClient): void;

    public function getPath(): string;

    public function setPath(string $path): void;

    public function getMethod(): string;

    public function setMethod(string $method): void;

    public function getHeaders(): array;

    public function setHeaders(array $headers): void;

    public function getQueries(): array;

    public function setQueries(array $queries): void;

    public function getRequests(): array;

    public function setRequests(array $requests): void;

    public function getFiles(): array;

    public function setFiles(array $files): void;
}
