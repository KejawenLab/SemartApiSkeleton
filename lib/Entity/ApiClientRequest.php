<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\Repository\ApiClientRequestRepository;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ApiClientRequestRepository::class)
 * @ORM\Table(name="core_api_client_request")
 */
class ApiClientRequest implements ApiClientRequestInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"read"})
     *
     * @OA\Property(type="string")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity=ApiClient::class, cascade={"persist"})
     *
     * @Groups({"read"})
     * @MaxDepth(1)
     */
    private ApiClientInterface $apiClient;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="string")
     */
    private string $path;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", length=9)
     */
    private string $method;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="json")
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private array $headers;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="json")
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private array $queries;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="json")
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private array $requests;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="json")
     *
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
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
}
