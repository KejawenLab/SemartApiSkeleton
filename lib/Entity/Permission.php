<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\PermissionRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: 'core_permission')]
class Permission implements PermissionInterface
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
    #[ORM\ManyToOne(targetEntity: Group::class, cascade: ['persist'])]
    private ?GroupInterface $group;

    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\ManyToOne(targetEntity: Menu::class, cascade: ['persist'])]
    private ?MenuInterface $menu;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $addable;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $editable;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $viewable;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
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
        return (string)$this->id;
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
