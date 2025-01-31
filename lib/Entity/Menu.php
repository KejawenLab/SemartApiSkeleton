<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\MenuRepository;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Validator\Route;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: 'core_menu')]
#[UniqueEntity(['code'])]
class Menu implements MenuInterface
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
    #[MaxDepth(1)]
    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist'])]
    private ?MenuInterface $parent;

    #[Assert\Length(max: 27)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 27)]
    private ?string $code;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'integer')]
    private int $sortOrder;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    #[Route]
    private ?string $routeName;

    #[Assert\Length(max: 27)]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 27, nullable: true)]
    private ?string $iconClass;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $showable;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $adminOnly;

    #[Groups(groups: ['read'])]
    private ?string $apiPath = '#';

    #[Groups(groups: ['read'])]
    private ?string $adminPath = '#';

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
