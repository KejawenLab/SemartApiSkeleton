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
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CronReportRepository::class)
 * @ORM\Table(name="core_cronjob_report")
 */
class CronReport implements CronReportInterface
{
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
     * @ORM\ManyToOne(targetEntity=Cron::class, cascade={"persist"})
     *
     * @Groups({"read"})
     */
    private ?CronInterface $cron = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"read"})
     */
    private DateTimeInterface $runAt;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"read"})
     */
    private float $runtime;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"read"})
     */
    private ?string $output = null;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Groups({"read"})
     */
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
