<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Repository\CronReportRepository;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: CronReportRepository::class)]
#[Table(name: 'core_cronjob_report')]
class CronReport implements CronReportInterface
{
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
    #[ManyToOne(targetEntity: Cron::class, cascade: ['persist'])]
    private ?CronInterface $cron = null;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'datetime')]
    private DateTimeInterface $runAt;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'float')]
    private float $runtime;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'text')]
    private ?string $output = null;

    /**
     * @Groups({"read"})
     */
    #[Column(type: 'smallint')]
    private int $exitCode;

    public function getId(): ?string
    {
        return (string) $this->id;
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

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(string $output): void
    {
        $this->output = $output;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function setExitCode(int $exitCode): void
    {
        $this->exitCode = $exitCode;
    }

    public function isSuccessful(): bool
    {
        return 0 === $this->getExitCode();
    }

    public function isError(): bool
    {
        return !$this->isSuccessful();
    }

    public function getNullOrString(): ?string
    {
        return $this->getOutput();
    }
}
