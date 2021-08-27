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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\SettingRepository;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"parameter"})
 */
#[Entity(repositoryClass: SettingRepository::class)]
#[Table(name: 'core_setting')]
class Setting implements SettingInterface
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
     */
    #[Column(name: 'setting_group', type: 'string', length: 27)]
    #[Length(max: 27)]
    #[NotBlank]
    private ?string $group;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 27)]
    #[Length(max: 27)]
    #[NotBlank]
    private ?string $parameter;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'text')]
    #[NotBlank]
    private ?string $value;

    #[Column(type: 'boolean')]
    private bool $public;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
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
