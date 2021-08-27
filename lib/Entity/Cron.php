<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"name"})
 * @ConsoleCommand()
 */
#[Entity(repositoryClass: CronRepository::class)]
#[Table(name: 'core_cronjob')]
class Cron implements CronInterface
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
     */
    #[Column(type: 'string', length: 255)]
    #[Length(max: 255)]
    #[NotBlank]
    private ?string $name;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255, nullable: true)]
    #[Length(max: 255)]
    private ?string $description;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255)]
    #[Length(max: 255)]
    #[NotBlank]
    private ?string $command;

    /**
     * @CronScheduleFormat()
     * @Groups({"read"})
     */
    #[Column(type: 'string', length: 255)]
    #[Length(max: 255)]
    #[NotBlank]
    private ?string $schedule;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $enabled;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
    private bool $symfonyCommand;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'boolean')]
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

    public function setName(string $name): void
    {
        $this->name = StringUtil::title($name);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isSymfonyCommand(): bool
    {
        return $this->symfonyCommand;
    }

    public function setSymfonyCommand(bool $symfonyCommand): void
    {
        $this->symfonyCommand = $symfonyCommand;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function setRunning(bool $running): void
    {
        $this->running = $running;
    }

    public function getNullOrString(): ?string
    {
        return $this->getName();
    }
}
