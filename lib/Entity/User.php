<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
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
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="core_user")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
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
     * @ORM\ManyToOne(targetEntity=Group::class, cascade={"persist"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private ?GroupInterface $group;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist"})
     *
     * @Groups({"read"})
     */
    private ?UserInterface $supervisor;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Length(max=180)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"read"})
     */
    private ?string $profileImage;

    /**
     * @ORM\Column(type="string", length=55)
     *
     * @Assert\Length(max=55)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $fullName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Groups({"read"})
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
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

    public function setProfileImage(string $profileImage): void
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
