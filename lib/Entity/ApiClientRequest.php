<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\Repository\ApiClientRequestRepository;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ApiClientRequestRepository::class)]
#[ORM\Table(name: 'core_api_client_request')]
class ApiClientRequest implements ApiClientRequestInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'string')]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    private UuidInterface $id;

    #[Groups(groups: ['read'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(targetEntity: ApiClient::class, cascade: ['persist'])]
    private ApiClientInterface $apiClient;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string')]
    private string $path;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 9)]
    private string $method;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    #[ORM\Column(type: 'json')]
    private array $headers;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    #[ORM\Column(type: 'json')]
    private array $queries;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    #[ORM\Column(type: 'json')]
    private array $requests;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'))]
    #[ORM\Column(type: 'json')]
    private array $files;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'text')]
    private string $content;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'smallint', length: 3)]
    private int $statusCode;

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getApiClient(): ?ApiClientInterface
    {
        return $this->apiClient;
    }

    public function setApiClient(ApiClientInterface $apiClient): void
    {
        $this->apiClient = $apiClient;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function setQueries(array $queries): void
    {
        $this->queries = $queries;
    }

    public function getRequests(): array
    {
        return $this->requests;
    }

    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    public function getNullOrString(): ?string
    {
        return null;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
