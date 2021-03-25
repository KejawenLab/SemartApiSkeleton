<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Application\Repository\PostRepository;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="app_post")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"code"})
 */
class Post implements GroupInterface, EntityInterface
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
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist"})
     *
     * @Groups({"read"})
     * @MaxDepth(1)
     */
    private ?Category $category;

    /**
     * @ORM\Column(type="string", length=7)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $code;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $name;

    public function __construct()
    {
        $this->code = null;
        $this->name = null;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = StringUtil::uppercase($code);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = StringUtil::title($name);

        return $this;
    }

    public function __sleep()
    {
        return [];
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
