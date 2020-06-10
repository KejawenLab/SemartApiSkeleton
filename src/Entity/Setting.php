<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Alpabit\ApiSkeleton\Repository\SettingRepository;
use Alpabit\ApiSkeleton\Setting\Model\SettingInterface;
use Alpabit\ApiSkeleton\Util\StringUtil;
use DH\DoctrineAuditBundle\Annotation\Auditable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 * @ORM\Table(name="core_setting")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Auditable()
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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $parameter;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    public function __construct()
    {
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

    public function setParameter(string $parameter): self
    {
        $this->parameter = StringUtil::uppercase($parameter);

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }
}
