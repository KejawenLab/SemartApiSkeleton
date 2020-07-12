<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Alpabit\ApiSkeleton\Client\Model\ClientInterface;
use Alpabit\ApiSkeleton\Repository\ClientRepository;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use DH\DoctrineAuditBundle\Annotation\Auditable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\Table(name="core_api_client")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Auditable()
 */
class Client implements ClientInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"read"})
     *
     * @SWG\Property(type="string")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     *
     * @Groups({"read"})
     * @MaxDepth(1)
     */
    private ?UserInterface $user;

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

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroup(): ?GroupInterface
    {
        if ($user = $this->getUser()) {
            return $user->getGroup();
        }

        return null;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function getIdentity(): ?string
    {
        return $this->getApiKey();
    }

    public function getCredential(): ?string
    {
        return $this->getSecretKey();
    }

    public function isEncoded(): bool
    {
        return false;
    }
}
