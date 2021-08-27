<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Repository\MediaRepository;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @UniqueEntity(fields={"fileName"})
 *
 * @Vich\Uploadable
 */
#[Entity(repositoryClass: MediaRepository::class)]
#[Table(name: 'core_media')]
class Media implements MediaInterface
{
    use BlameableEntity;
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

    #[Column(type: 'string', length: 255)]
    private ?string $fileName;

    #[Column(type: 'string', length: 255)]
    private ?string $folder;

    #[Column(type: 'boolean')]
    private bool $public;

    #[Column(type: 'boolean')]
    private bool $hidden;

    /**
     * @Groups({"read"})
     */
    private ?string $fileUrl;

    /**
     * @Vich\UploadableField(mapping="media", fileNameProperty="fileName")
     */
    #[NotBlank]
    private ?File $file = null;

    public function __construct()
    {
        $this->fileName = null;
        $this->folder = null;
        $this->public = false;
        $this->hidden = false;
        $this->fileUrl = null;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFolder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): void
    {
        $this->fileUrl = $fileUrl;
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
        return $this->getFileUrl();
    }
}
