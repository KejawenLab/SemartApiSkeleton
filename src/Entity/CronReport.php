<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Repository\CronReportRepository;
use Ramsey\Uuid\UuidInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CronReportRepository::class)
 * @ORM\Table(name="core_cronjob_report")
 */
class CronReport implements CronReportInterface, EntityInterface
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
    private ?CronInterface $cron;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"read"})
     */
    private \DateTime $runAt;

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
    private ?string $output;

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

    public function setCron(CronInterface $cron): self
    {
        $this->cron = $cron;

        return $this;
    }

    public function getRunAt(): \DateTime
    {
        return $this->runAt;
    }

    public function setRunAt(\DateTime $runAt): self
    {
        $this->runAt = $runAt;

        return $this;
    }

    public function getRuntime(): float
    {
        return (float) $this->runtime;
    }

    public function setRuntime(float $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(string $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getExitCode(): int
    {
        return (int) $this->exitCode;
    }

    public function setExitCode(int $exitCode): self
    {
        $this->exitCode = $exitCode;

        return $this;
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
