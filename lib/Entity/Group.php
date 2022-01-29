<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\GroupRepository;
use KejawenLab\ApiSkeleton\Security\GroupIdGenerator;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: 'core_group')]
#[UniqueEntity(['code'])]
class Group implements GroupInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'string')]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: GroupIdGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    private UuidInterface $id;

    #[Assert\Length(max: 7)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 7)]
    private ?string $code;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255)]
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

    public function __sleep()
    {
        return [];
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
