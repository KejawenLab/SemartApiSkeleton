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
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 * @ORM\Table(name="core_setting")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"parameter"})
 */
class Setting implements SettingInterface
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
     * @OA\Property(type="string")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $parameter;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $public;

    public function __construct()
    {
        $this->parameter = null;
        $this->value = null;
        $this->public = false;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
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

    public function getNullOrString(): ?string
    {
        return sprintf('%s: %s', $this->getParameter(), $this->getValue());
    }
}
