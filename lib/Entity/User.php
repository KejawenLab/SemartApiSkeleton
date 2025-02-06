<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Repository\UserRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'core_user')]
#[UniqueEntity('email')]
#[UniqueEntity('username')]
class User implements UserInterface
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

    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\ManyToOne(targetEntity: Group::class, cascade: ['persist'])]
    private ?GroupInterface $group;

    #[Groups(groups: ['read'])]
    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist'])]
    private ?UserInterface $supervisor;

    #[Assert\Length(max: 180)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $username;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $profileImage;

    #[Assert\Length(max: 55)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 55)]
    private ?string $fullName;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $lastLogin;

    #[ORM\Column(type: 'string')]
    private ?string $password;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $deviceId = null;

    private ?File $file = null;

    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->group = null;
        $this->supervisor = null;
        $this->username = null;
        $this->profileImage = null;
        $this->fullName = null;
        $this->email = null;
        $this->password = null;
        $this->lastLogin = new DateTimeImmutable();
    }

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function setGroup(?GroupInterface $group): void
    {
        $this->group = $group;
    }

    public function getSupervisor(): ?UserInterface
    {
        return $this->supervisor;
    }

    public function setSupervisor(?UserInterface $supervisor): void
    {
        $this->supervisor = $supervisor;
    }

    public function getProfileImage(): ?string
    {
        if ($this->profileImage === null || $this->profileImage === '') {
            return $this->profileImage;
        }

        $slice = explode('/', $this->profileImage);

        return sprintf('/%s/%s', self::PROFILE_MEDIA_FOLDER, array_pop($slice));
    }

    public function setProfileImage(?string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLastLogin(): DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTimeImmutable $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getIdentity(): ?string
    {
        return $this->getUsername();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = StringUtil::lowercase($username);
    }

    public function getRecordId(): ?string
    {
        return $this->getId();
    }

    public function getId(): ?string
    {
        return (string)$this->id;
    }

    public function getCredential(): ?string
    {
        return $this->getPassword();
    }

    public function getPassword(): ?string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isEncoded(): bool
    {
        return true;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function getNullOrString(): ?string
    {
        return $this->getFullName();
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = StringUtil::title($fullName);
    }
}
