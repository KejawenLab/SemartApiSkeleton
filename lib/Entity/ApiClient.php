<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Repository\ApiClientRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[UniqueEntity(['user', 'name'])]
#[ORM\Entity(repositoryClass: ApiClientRepository::class)]
#[ORM\Table(name: 'core_api_client')]
class ApiClient implements ApiClientInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;
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
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private ?UserInterface $user;

    #[Assert\Length(max: 27)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 27)]
    private ?string $name;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 40)]
    private ?string $apiKey;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 64)]
    private ?string $secretKey;

    public function __construct()
    {
        $this->user = null;
        $this->name = null;
        $this->apiKey = null;
        $this->secretKey = null;
    }

    public function getGroup(): ?GroupInterface
    {
        $user = $this->getUser();
        if (null !== $user) {
            return $user->getGroup();
        }

        return null;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getIdentity(): ?string
    {
        return $this->getApiKey();
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getRecordId(): ?string
    {
        return $this->getId();
    }

    public function getId(): ?string
    {
        return (string)$this->id;
    }

    public function getCredential(): ?string
    {
        return $this->getSecretKey();
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function isEncoded(): bool
    {
        return false;
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = StringUtil::title($name);
    }
}
