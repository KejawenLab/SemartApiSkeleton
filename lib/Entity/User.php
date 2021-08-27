<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use DateTimeImmutable;
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
use KejawenLab\ApiSkeleton\Repository\UserRepository;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'core_user')]
class User implements UserInterface
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
    #[NotBlank]
    private ?GroupInterface $group;

    /**
     * @Groups({"read"})
     */
    #[ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private ?UserInterface $supervisor;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 180, unique: true)]
    #[Length(max: 180)]
    #[NotBlank]
    private ?string $username;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255, nullable: true)]
    private ?string $profileImage;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 55)]
    #[Length(max: 55)]
    #[NotBlank]
    private ?string $fullName;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255, unique: true)]
    #[Length(max: 255)]
    #[NotBlank]
    #[Email]
    private ?string $email;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $lastLogin;

    #[Column(type: 'string')]
    private ?string $password;

    #[Column(type: 'string', nullable: true)]
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

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = StringUtil::lowercase($username);
    }

    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
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
        if (!$this->profileImage) {
            return $this->profileImage;
        }

        $slice = explode('/', $this->profileImage);

        return sprintf('/%s/%s', self::PROFILE_MEDIA_FOLDER, array_pop($slice));
    }

    public function setProfileImage(?string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = StringUtil::title($fullName);
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

    public function getRecordId(): ?string
    {
        return $this->getId();
    }

    public function getCredential(): ?string
    {
        return $this->getPassword();
    }

    public function isEncoded(): bool
    {
        return true;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    public function getNullOrString(): ?string
    {
        return $this->getFullName();
    }
}
