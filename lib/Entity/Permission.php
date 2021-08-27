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
use KejawenLab\ApiSkeleton\Repository\PermissionRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[Entity(repositoryClass: PermissionRepository::class)]
#[Table(name: 'core_permission')]
class Permission implements PermissionInterface
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
     **/
    #[ManyToOne(targetEntity: Group::class, cascade: ['persist'])]
    private ?GroupInterface $group;

    /**
     * @Groups({"read"})
     **/
    #[ManyToOne(targetEntity: Menu::class, cascade: ['persist'])]
    #[NotBlank]
    private ?MenuInterface $menu;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $addable;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $editable;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $viewable;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $deletable;

    public function __construct()
    {
        $this->group = null;
        $this->menu = null;
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

    public function setGroup(?GroupInterface $group): void
    {
        $this->group = $group;
    }

    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    public function setMenu(?MenuInterface $menu): void
    {
        $this->menu = $menu;
    }

    public function isAddable(): bool
    {
        return $this->addable;
    }

    public function setAddable(bool $addable): void
    {
        $this->addable = $addable;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): void
    {
        $this->editable = $editable;
    }

    public function isViewable(): bool
    {
        return $this->viewable;
    }

    public function setViewable(bool $viewable): void
    {
        $this->viewable = $viewable;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(bool $deletable): void
    {
        $this->deletable = $deletable;
    }

    public function getNullOrString(): ?string
    {
        return null;
    }
}
