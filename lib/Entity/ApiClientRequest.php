<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\Repository\ApiClientRequestRepository;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[Entity(repositoryClass: ApiClientRequestRepository::class)]
#[Table(name: 'core_api_client_request')]
class ApiClientRequest implements ApiClientRequestInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    /**
     * @Groups({"read"})
     *
     * @OA\Property(type="string")
     */
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    /**
     * @Groups({"read"})
     * @MaxDepth(1)
     */
    #[ManyToOne(targetEntity: ApiClient::class, cascade: ['persist'])]
    private ApiClientInterface $apiClient;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string')]
    private string $path;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 9)]
    private string $method;

    /**
     * @Groups({"read"})
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    #[Column(type: 'json')]
    private array $headers;

    /**
     * @Groups({"read"})
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    #[Column(type: 'json')]
    private array $queries;

    /**
     * @Groups({"read"})
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    #[Column(type: 'json')]
    private array $requests;

    /**
     * @Groups({"read"})
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    #[Column(type: 'json')]
    private array $files;

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

    /**
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param mixed[] $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return mixed[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @param mixed[] $queries
     */
    public function setQueries(array $queries): void
    {
        $this->queries = $queries;
    }

    /**
     * @return mixed[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @param mixed[] $requests
     */
    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    /**
     * @return mixed[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param mixed[] $files
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    public function getNullOrString(): ?string
    {
        return null;
    }
}
