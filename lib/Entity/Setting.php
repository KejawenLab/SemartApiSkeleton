<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\SettingRepository;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[ORM\Table(name: 'core_setting')]
#[UniqueEntity(['parameter'])]
class Setting implements SettingInterface
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

    #[Assert\Length(max: 27)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(name: 'setting_group', type: 'string', length: 27)]
    private ?string $group;

    #[Assert\Length(max: 27)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 27)]
    private ?string $parameter;

    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'text')]
    private ?string $value;

    #[ORM\Column(type: 'boolean')]
    private bool $public;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $reserved;

    public function __construct()
    {
        $this->group = null;
        $this->parameter = null;
        $this->value = null;
        $this->public = false;
        $this->reserved = false;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = StringUtil::lowercase($group);
    }

    public function getParameter(): ?string
    {
        return $this->parameter;
    }

    public function setParameter(string $parameter): void
    {
        $this->parameter = StringUtil::uppercase($parameter);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    public function isReserved(): bool
    {
        return $this->reserved;
    }

    public function setReserved(bool $reserved): void
    {
        $this->reserved = $reserved;
    }

    public function getNullOrString(): ?string
    {
        return sprintf('%s: %s', $this->getParameter(), $this->getValue());
    }
}
