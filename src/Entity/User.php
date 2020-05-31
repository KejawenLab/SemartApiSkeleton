<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Alpabit\ApiSkeleton\Repository\UserRepository;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface as AppUser;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
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
class User implements UserInterface, AppUser
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
     * @ORM\ManyToOne(targetEntity=Group::class, cascade={"persist"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     *
     * @Groups({"read"})
     */
    private $supervisor;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Length(max=180)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=55)
     *
     * @Assert\Length(max=55)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Groups({"read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    private $plainPassword;

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = StringUtil::lowercase($username);

        return $this;
    }

    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function setGroup(?GroupInterface $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getSupervisor(): ?AppUser
    {
        return $this->supervisor;
    }

    public function setSupervisor(?AppUser $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = StringUtil::title($fullName);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
