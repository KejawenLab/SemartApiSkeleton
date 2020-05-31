# Semart Api Skeleton

## Screenshot

#### Api Doc
![Api Doc](doc/assets/full.png)

#### Sandbox
![Sandbox](doc/assets/sandbox.png)

#### Cronjob Management
![Cronjob](doc/assets/cron.png)

#### Setting Management
![Setting](doc/assets/setting.png)

#### Group Management
![Group](doc/assets/group.png)

#### Menu Management
![Menu](doc/assets/menu.png)

#### User Management
![User](doc/assets/user.png)

#### Profile Management
![Profile](doc/assets/profile.png)

## Requirement

> 
> * PHP >= 7.25
>
> * Extension Ctype 
>
> * Extension Iconv
>
> * Extension Json
>
> * Extension Openssl
>
> * Extension Pcntl
>
> * Extension Pdo
>
> * Extension Redis
>
> * RDBMS (MySQL/MariaDB/PostgreSQL/OracleDB/SQLServer)
>
> * Redis Server >= 4.0
>

## Install

#### Pengguna MySQL/MariaDB

```bash
git clone https://github.com/KejawenLab/SemartApiSkeleton
cd SemartApiSkeleton
composer update --prefer-dist -vvv
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixtures:load
```

#### Pengguna PostgreSQL/OracleDB/SQLServer

```bash
git clone https://github.com/KejawenLab/SemartApiSkeleton
cd SemartApiSkeleton
composer update --prefer-dist -vvv
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

## Fitur

>
> * RESTful Api Generator
>
> * Api Documentation
>
> * Sandbox
>
> * JWT Authentication
>
> * Login Failure Limiter
>
> * Single Sign In
>
> * Query Extension
>
> * Soft Deletable
>
> * Activity Log
>
> * User Management
>
> * Profile Management
>
> * Group Management
> 
> * Menu Management
>
> * Permission Management
>
> * Setting Management
>
> * Cronjob Management
>
> * Cache Management
>

## Cara Penggunaan

#### Buat Class Model

```php
<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Test;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface TestInterface
{
    public function getId(): ?string;

    public function getCode(): ?string;

    public function getName(): ?string;
}

```

#### Buat Class Entity

```php
<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Alpabit\ApiSkeleton\Repository\TestRepository;
use Alpabit\ApiSkeleton\Test\TestInterface;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 * @ORM\Table(name="test_table")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity(fields={"code"})
 */
class Test implements TestInterface
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
     * @ORM\Column(type="string", length=7)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $name;

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = StringUtil::uppercase($code);

        return $this;
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

    public function __toString()
    {
        return $this->getId();
    }
}

```

#### Generate RESTful Api

```bash
php bin/console semart:generate Test
```

## TODO

[ ] Front User/Customer Management

[ ] Front User/Customer Reset Password

[ ] Generator Support Parent Menu

[ ] File/Media Management

[ ] Upgrade (Database) Management

## Copyright

Project ini disupport dan didedikasikan untuk PT. Alpabit Digital Inovasi
