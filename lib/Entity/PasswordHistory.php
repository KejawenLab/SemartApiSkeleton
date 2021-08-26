<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\PasswordHistoryRepository;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use Ramsey\Uuid\UuidInterface;

#[Entity(repositoryClass: PasswordHistoryRepository::class)]
#[Table(name: 'core_user_password_history')]
class PasswordHistory implements PasswordHistoryInterface
{
    use BlameableEntity;
    use TimestampableEntity;
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    #[Column(type: 'string')]
    private ?string $source = null;

    #[Column(type: 'string', length: 49)]
    private ?string $identifier = null;

    #[Column(type: 'string')]
    private ?string $password = null;

    public function getId(): ?string
    {
        return (string) $this->id;
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
