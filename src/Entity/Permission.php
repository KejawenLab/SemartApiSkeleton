<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Alpabit\ApiSkeleton\Repository\PermissionRepository;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInterface;
use DH\DoctrineAuditBundle\Annotation\Auditable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
 * @ORM\Table(name="core_permission")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Auditable()
 */
class Permission implements PermissionInterface
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
     * @ORM\ManyToOne(targetEntity=Group::class, cascade={"persist", "remove"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, cascade={"persist", "remove"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $menu;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $addable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $editable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $viewable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $deletable;

    public function __construct()
    {
        $this->addable = false;
        $this->editable = false;
        $this->viewable = false;
        $this->deletable = false;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function setGroup(?GroupInterface $group): PermissionInterface
    {
        $this->group = $group;

        return $this;
    }

    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    public function setMenu(?MenuInterface $menu): PermissionInterface
    {
        $this->menu = $menu;

        return $this;
    }

    public function isAddable(): bool
    {
        return $this->addable;
    }

    public function setAddable(bool $addable): self
    {
        $this->addable = (bool) $addable;

        return $this;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): self
    {
        $this->editable = (bool) $editable;

        return $this;
    }

    public function isViewable(): bool
    {
        return $this->viewable;
    }

    public function setViewable(bool $viewable): self
    {
        $this->viewable = (bool) $viewable;

        return $this;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(bool $deletable): self
    {
        $this->deletable = (bool) $deletable;

        return $this;
    }
}
