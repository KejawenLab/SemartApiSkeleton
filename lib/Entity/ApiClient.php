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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity({"user", "name"})
 */
#[Entity(repositoryClass: ApiClientRepository::class)]
#[Table(name: 'core_api_client')]
class ApiClient implements ApiClientInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;
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
    #[ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private ?UserInterface $user;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 27)]
    #[Length(max: 27)]
    #[NotBlank]
    private ?string $name;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 40)]
    private ?string $apiKey;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 64)]
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
        $user = $this->getUser();
        if (null !== $user) {
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
