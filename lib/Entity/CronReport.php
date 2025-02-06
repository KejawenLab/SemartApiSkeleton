<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Repository\CronReportRepository;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CronReportRepository::class)]
#[ORM\Table(name: 'core_cronjob_report')]
class CronReport implements CronReportInterface
{
    use TimestampableEntity;

    #[Groups(groups: ['read'])]
    #[OA\Property(type: 'string')]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    private UuidInterface $id;

    #[Groups(groups: ['read'])]
    #[ORM\ManyToOne(targetEntity: Cron::class, cascade: ['persist'])]
    private ?CronInterface $cron = null;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $runAt;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'float')]
    private float $runtime;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'text')]
    private ?string $output = null;

    #[Groups(groups: ['read'])]
    #[ORM\Column(type: 'smallint')]
    private int $exitCode;

    public function getId(): ?string
    {
        return (string)$this->id;
    }

    public function getCron(): ?CronInterface
    {
        return $this->cron;
    }

    public function setCron(CronInterface $cron): void
    {
        $this->cron = $cron;
    }

    public function getRunAt(): DateTimeInterface
    {
        return $this->runAt;
    }

    /**
     * @param DateTimeInterface $runAt
     */
    public function setRunAt(DateTime|DateTimeImmutable $runAt): void
    {
        $this->runAt = $runAt;
    }

    public function getRuntime(): float
    {
        return $this->runtime;
    }

    public function setRuntime(float $runtime): void
    {
        $this->runtime = $runtime;
    }

    public function isError(): bool
    {
        return !$this->isSuccessful();
    }

    public function isSuccessful(): bool
    {
        return 0 === $this->getExitCode();
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function setExitCode(int $exitCode): void
    {
        $this->exitCode = $exitCode;
    }

    public function getNullOrString(): ?string
    {
        return $this->getOutput();
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(string $output): void
    {
        $this->output = $output;
    }
}
