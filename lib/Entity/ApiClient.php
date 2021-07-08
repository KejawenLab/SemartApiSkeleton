<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Repository\ApiClientRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ApiClientRepository::class)
 * @ORM\Table(name="core_api_client")
 *
 * @UniqueEntity({"user", "name"})
 */
class ApiClient implements ApiClientInterface
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
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     * @MaxDepth(1)
     */
    private ?UserInterface $user;

    /**
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=40)
     *
     * @Groups({"read"})
     */
    private ?string $apiKey;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Groups({"read"})
     */
    private ?string $secretKey;

    public function __construct()
    {
        $this->user = null;
        $this->name = null;
        $this->apiKey = null;
        $this->secretKey = null;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getGroup(): ?GroupInterface
    {
        if ($user = $this->getUser()) {
            return $user->getGroup();
        }

        return null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = StringUtil::title($name);
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getIdentity(): ?string
    {
        return $this->getApiKey();
    }

    public function getRecordId(): ?string
    {
        return $this->getId();
    }

    public function getCredential(): ?string
    {
        return $this->getSecretKey();
    }

    public function isEncoded(): bool
    {
        return false;
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
