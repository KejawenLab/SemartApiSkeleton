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

#### Media Management
![Setting](doc/assets/media.png)

#### Group Management
![Group](doc/assets/group.png)

#### Menu Management
![Menu](doc/assets/menu.png)

#### User Management
![User](doc/assets/user.png)

#### Profile Management
![Profile](doc/assets/profile.png)

## Requirement

#### Abaikan Requirement jika Anda menggunakan Docker

> 
> * PHP >= 7.2.5
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
> * Extension Posix
>
> * Extension Redis
>
> * RDBMS (MySQL/MariaDB/PostgreSQL/OracleDB/SQLServer)
>
> * Redis Server >= 4.0
>
> * Composer
>
> * Symfony Console 
>

## Install

### Pre Step

> 
> Clone dan Generate PKI (Public Key Infrastucture)
>

```bash
git clone https://github.com/KejawenLab/SemartApiSkeleton
cd SemartApiSkeleton
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

### Non Docker Install

>
> Install menggunakan metode Non Docker secara default akan menggunakan secure connection configuration
>
> Ini artinya password database akan dienkripsi sehingga lebih aman
>

#### Pengguna MySQL/MariaDB

```bash
composer update --prefer-dist -vvv
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixtures:load
php bin/console assets:install
php bin/console cron:start
symfony server:start
```

#### Pengguna PostgreSQL/OracleDB/SQLServer

```bash
composer update --prefer-dist -vvv
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
php bin/console assets:install
php bin/console cron:start
symfony server:start
```

> 
> Buka browser pada halaman https://localhost:8000/api/doc atau sesuai alamat yang tertera ketika menjalankan perintah `symfony server:start`
>

### Docker Install

>
> Install menggunakan metode Docker adalah cara tercepat untuk memulai tanpa perlu install dependencies terlebih dahulu
>
> * Ubah file `.env.template` menjadi file `.env`
>
> * Ubah isi file `.env` sesuai dengan kebutuhan
>
> * Jalankan perintah berikut:
>

```bash
docker-compose build && docker-compose up
docker exec -it semart_app_1 bash -c "php bin/console semart:encrypt [DATABASE_PASSWORD]"
```

> 
> * Abaikan warning atau error yang terjadi
> 
> * Ubah nilai `DATABASE_PASSWORD` pada file `.env` sesuai dengan hasil perintah di atas
>
> * Jalankan perintah berikut:
>

```bash
docker-compose build && docker-compose up
docker exec -it semart_app_1 bash -c "php bin/console doctrine:database:create"
docker exec -it semart_app_1 bash -c "php bin/console doctrine:schema:update --force"
docker exec -it semart_app_1 bash -c "php bin/console doctrine:fixtures:load"
docker exec -it semart_app_1 bash -c "php bin/console assets:install"
```

> 
> * Aplikasi berjalan pada alamat `http://localhost:9876/api/doc`
>
> * Adminer berjalan pada alamat `http://localhost:6789`
>

## Cron Daemon

#### Start Cron Daemon

```bash
php bin/console cron:start
```

#### Stop Cron Daemon

```bash
php bin/console cron:stop
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
> * Media Management
>
> * Public & Private Media Support
>
> * Upgrade Management
>

## Cara Penggunaan

#### Buat Interface Model

```php
<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Test\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface TestInterface
{
    public function getId(): ?string;

    public function getName(): ?string;
}

```

#### Buat Class Entity

```php
<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity;

use Alpabit\ApiSkeleton\Repository\TestRepository;
use Alpabit\ApiSkeleton\Test\Model\TestInterface;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 * @ORM\Table(name="test_table")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
final class Test implements TestInterface
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = StringUtil::title($name);

        return $this;
    }
}

```

#### Generate RESTful Api

```bash
php bin/console semart:generate Test
```

#### Update form type

```php
//class: Alpabit\ApiSkeleton\Form\Type\TestType 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @todo implement your form here
         */
    }
```

#### Update search query extension

```php
//class: Alpabit\ApiSkeleton\Test\Query\SearchQueryExtension
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        /**
        * Uncomment to implement your own search logic
        *
        * $alias = $this->aliasHelper->findAlias('root');
        * $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.name)', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
        */
    }
```

## TODO

- [ ] Front User/Customer Management

- [ ] Front User/Customer Reset Password

## Copyright

Project ini disupport dan didedikasikan untuk PT. Alpabit Digital Inovasi
