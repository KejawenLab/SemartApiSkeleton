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
use KejawenLab\ApiSkeleton\Repository\MenuRepository;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Validator\Route;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"code"})
 */
#[Entity(repositoryClass: MenuRepository::class)]
#[Table(name: 'core_menu')]
class Menu implements MenuInterface
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
     * @MaxDepth(1)
     */
    #[ManyToOne(targetEntity: Menu::class, cascade: ['persist'])]
    private ?MenuInterface $parent;

    /**
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 27)]
    private ?string $code;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255)]
    private ?string $name;

    /**
     * @Assert\NotBlank()
     * @Groups({"read"})
     */
    #[Column(type: 'integer')]
    private int $sortOrder;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @Route()
     */
    #[Column(type: 'string', length: 255)]
    private ?string $routeName;

    /**
     * @Assert\Length(max=27)
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 27, nullable: true)]
    private ?string $iconClass;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $showable;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $adminOnly;

    /**
     * @Groups({"read"})
     */
    private ?string $apiPath;
    private ?string $adminPath;

    public function __construct()
    {
        $this->parent = null;
        $this->code = null;
        $this->name = null;
        $this->sortOrder = 0;
        $this->routeName = '#';
        $this->iconClass = null;
        $this->showable = true;
        $this->adminOnly = false;
        $this->apiPath = '#';
        $this->adminPath = '#';
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getParent(): ?MenuInterface
    {
        return $this->parent;
    }

    public function setParent(?MenuInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = StringUtil::uppercase($code);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = StringUtil::title($name);
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function getApiPath(): ?string
    {
        return $this->apiPath;
    }

    public function setApiPath(string $apiPath): void
    {
        $this->apiPath = $apiPath;
    }

    public function getAdminPath(): ?string
    {
        return $this->adminPath;
    }

    public function setAdminPath(?string $adminPath): void
    {
        $this->adminPath = $adminPath;
    }

    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function setIconClass(?string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    public function isShowable(): ?bool
    {
        return $this->showable;
    }

    public function setShowable(bool $showable): void
    {
        $this->showable = $showable;
    }

    public function isAdminOnly(): bool
    {
        return $this->adminOnly;
    }

    public function setAdminOnly(bool $adminOnly): void
    {
        $this->adminOnly = $adminOnly;
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
