<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use DH\Auditor\Provider\Doctrine\Auditing\Annotation\Auditable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Validator\ConsoleCommand;
use KejawenLab\ApiSkeleton\Cron\Validator\CronScheduleFormat;
use KejawenLab\ApiSkeleton\Repository\CronRepository;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CronRepository::class)
 * @ORM\Table(name="core_cronjob")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Auditable()
 *
 * @UniqueEntity(fields={"name"})
 * @ConsoleCommand()
 */
class Cron implements CronInterface, EntityInterface
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private ?string $command;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @CronScheduleFormat()
     *
     * @Groups({"read"})
     */
    private ?string $schedule;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private bool $enabled;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private bool $symfonyCommand;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private bool $running;

    public function __construct()
    {
        $this->name = null;
        $this->description = null;
        $this->command = null;
        $this->schedule = null;
        $this->enabled = true;
        $this->symfonyCommand = true;
        $this->running = false;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isSymfonyCommand(): bool
    {
        return $this->symfonyCommand;
    }

    public function setSymfonyCommand(bool $symfonyCommand): self
    {
        $this->symfonyCommand = $symfonyCommand;

        return $this;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function setRunning(bool $running): self
    {
        $this->running = $running;

        return $this;
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
