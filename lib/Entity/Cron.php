<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

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
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ConsoleCommand]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
#[ORM\Entity(repositoryClass: CronRepository::class)]
#[ORM\Table(name: 'core_cronjob')]
#[UniqueEntity(['name'])]
class Cron implements CronInterface
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

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[Assert\Length(max: 255)]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $command;

    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[CronScheduleFormat]
    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $schedule;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $enabled;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $symfonyCommand;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'boolean')]
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
