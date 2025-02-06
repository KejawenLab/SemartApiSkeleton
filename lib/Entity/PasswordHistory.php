<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\PasswordHistoryRepository;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: PasswordHistoryRepository::class)]
#[ORM\Table(name: 'core_user_password_history')]
class PasswordHistory implements PasswordHistoryInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string')]
    private ?string $source = null;

    #[ORM\Column(type: 'string', length: 49)]
    private ?string $identifier = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    public function getId(): ?string
    {
        return (string)$this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getNullOrString(): ?string
    {
        return null;
    }
}
