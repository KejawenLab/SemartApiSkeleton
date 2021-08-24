# SemartApiSkeleton

>
> SemartApiSkeleton adalah solusi untuk membangun aplikasi secara super cepat, sangat fleksibel dan luar biasa mudah untuk aplikasi berbasis Admin maupun Api
> 
> SemartApiSkeleton menggunakan `swoole` sebagai PHP Runtime untuk menjalankan PHP dengan model long pooling sebagaimana NodeJs, Golang atau sejenisnya.
>

## Requirement

- [Docker](https://docs.docker.com/engine)

- [Docker Compose](https://docs.docker.com/compose)

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
#Jangan lupa untuk mengingat passphrase yang diinput
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

### Docker Install

>
> Install menggunakan metode Docker adalah cara tercepat untuk memulai tanpa perlu install dependencies terlebih dahulu
>
> * Ubah file `.env.template` menjadi file `.env`
>
> * Ubah isi file `.env` sesuai dengan kebutuhan terutama pada `JWT_PASSPHRASE`, berikut contoh konfigurasinya

```dotenv
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=3GXjF83smPRZWvHCZ7O+mA==
#TRUSTED_PROXIES=127.0.0.0/8,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16
#TRUSTED_HOSTS='^(localhost|example\.com)$'
###< symfony/framework-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=4e938d7532adc7163e0c657b1eb3ce93ed8f42c2
###< lexik/jwt-authentication-bundle ###

###> snc/redis-bundle ###
# passwords that contain special characters (@, %, :, +) must be urlencoded
REDIS_URL=redis://session
###< snc/redis-bundle ###

###> CUSTOM ###
NGINX_WEBROOT=/semart/public
APP_SUPER_ADMIN=SPRADM
APP_TITLE="Semart Api Skeleton"
APP_DESCRIPTION="Semart Api Skeleton"
APP_VERSION=1.0@dev
APP_UPLOAD_DIR=%kernel.project_dir%/storage
APP_MEDIA_PREFIX=/medias
###< CUSTOM ###

###> doctrine/doctrine-bundle ###
DATABASE_DRIVER=pdo_mysql
DATABASE_SERVER_VERSION=5.7
DATABASE_CHARSET=utf8mb4
DATABASE_USER=root
DATABASE_PASSWORD=4ZFbDniRw+vx+QnUY93Fhg==
DATABASE_NAME=semart
DATABASE_HOST=db
DATABASE_PORT=3306
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# For a PostgreSQL database, use: "postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8"
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
# DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
###< doctrine/doctrine-bundle ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$
###< nelmio/cors-bundle ###

```

>
> * Karena Semart Api Skeleton menggunakan encryption password untuk koneksi ke database, maka kita perlu mengenkripsi password database terlebih dahulu. Pada *file* `docker-compose.yml`, password database adalah `semart`, maka kita perlu menjalankan perintah berikut:
>

```bash
docker-compose -f docker-compose.yml build && docker-compose -f docker-compose.yml up
docker-compose -f docker-compose.yml exec app bash -c "php bin/console semart:encrypt semart"
```

> 
> * Abaikan warning atau error yang terjadi
> 
> * Ubah nilai `DATABASE_PASSWORD` pada file `.env` sesuai dengan hasil perintah di atas
>
> * Jalankan perintah berikut:
>

```bash
docker-compose -f docker-compose.yml exec app bash -c "php bin/console cache:clear"
docker-compose -f docker-compose.yml exec app bash -c "chmod 777 -R var"
docker-compose -f docker-compose.yml exec app bash -c "php bin/console doctrine:database:create"
docker-compose -f docker-compose.yml exec app bash -c "php bin/console doctrine:schema:update --force"
docker-compose -f docker-compose.yml exec app bash -c "php bin/console doctrine:fixtures:load --no-interaction"
docker-compose -f docker-compose.yml exec app bash -c "php bin/console assets:install"
```

> 
> * Swagger berjalan pada alamat `http://localhost:9876/api/doc` dengan username `admin` dan password `admin`
>
> * Admin berjalan pada alamat `http://localhost:9876/admin` dengan username `admin` dan password `admin` sama seperti pada Swagger
>
> * Adminer berjalan pada alamat `http://localhost:6789` dengan host `db`, username `root` dan password `semart`
>

## Cara Penggunaan

#### Buat Folder `Todo/Model` pada `app`

```bash
cd <your_installation_dir> && mkdir -p app/Todo/Model
```

#### Buat Interface `TodoInterface` pada folder `Todo/Model`

Model harus extends `KejawenLab\ApiSkeleton\Entity\EntityInterface` interface

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Application\Todo\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface TodoInterface extends EntityInterface
{
    public function getId(): ?string;

    public function getTask(): ?string;

    public function isDone(): bool;
}

```

#### Buat Entity pada folder `app/Entity`

Entity harus implement `KejawenLab\Application\Todo\Model\TodoInterface`. 

Kamu dapat merujuk pada dokumentasi resmi tentang [Mapping Entity](https://symfony.com/doc/current/doctrine.html#creating-an-entity-class), namun sangat tidak disarankan menggunakan Symfony Maker untuk membuat entity pada SemartApiSkeleton

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\Repository\TodoRepository;
use KejawenLab\Application\Todo\Model\TodoInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Ramsey\Uuid\UuidInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 * @ORM\Table(name="todos")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Todo implements TodoInterface
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
    private ?string $task;
    
    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private bool $done;
    
    public function __construct()
    {
        $this->task = null;
        $this->done = false;
    }

    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = StringUtil::title($task);
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function setDone(bool $done): void
    {
        $this->done = $done;
    }

    public function getNullOrString(): ?string
    {
        return $this->getTask();
    }
}

```

Class `KejawenLab\Application\Repository\TodoRepository` untuk sekarang belum ada, namun jangan khawatir, SemartApiSkeleton akan mengenerate class tersebut nanti.

#### Generate Controller, Form, Repository, Serivce, Register Menu, Template, dan Api Documentation

```bash
docker-compose -f docker-compose.yml exec app bash -c "php bin/console semart:generate Todo"
```

![Generate Command](doc/assets/generate_command.png)

Setelah menjalankan perintah di atas maka susunan folder `Todo/Model` akan menjadi seperti berikut:

![After Generate](doc/assets/after_generate.png)

Dan jika kamu login ke Semart Api Skeleton maka akan muncul menu `Todo` pada sidebar sebagai berikut:

![After Generate](doc/assets/sidebar_todo.png)

Dan ketika membuka menu `Menu` maka record `Todo` pun akan muncul sebagai berikut:

![Menu](doc/assets/menu_todo.png)

Dan pastinya kamu juga bisa set permission untuk `Todo` pada menu Hak Akses (`Group` -> `Lihat` -> `Hak Akses`) sebagai berikut:

![Todo Permission](doc/assets/rbac_todo.png)

Dan jika kamu mengakses halaman Api Documentation maka akan muncul section `Todo` sebagai berikut:

![Api Doc](doc/assets/api_todo.png)

Sangat mudah sekali bukan? Selanjutnya kamu harus restart dulu docker-nya agar class-class yang tadi digenerate dan didaftarkan sebagai service. Hal ini perlu dilakukan karena kita menggunakan swoole. 

#### Update translation

Ketika kamu mengetik menu `Todo` pada sidebar, maka akan muncul tampilan sebagai berikut:

![Todo List](doc/assets/todo_list.png)

Terdapat beberapa text yang aneh? Santai, kamu cukup buka file `translations/pages.id.yaml` dan tambahkan baris berikut:

```yaml
        todo:
            saved: 'Todo berhasil disimpan'
            deleted: 'Todo berhasil dihapus'
            not_found: 'Todo tidak ditemukan'
            list: 'Daftar Todo'
            add: 'Tambah Todo'
            edit: 'Ubah Todo'
            view: 'Detil Todo'
```

Selanjut, kamu juga perlu mengubah translasi pada file `translations/tables.id.yaml` sebagai berikut:

```yaml

            todo:
                task: 'Tugas'
                done: 'Selesai?'
```

Dan ketika kamu mengeklik menu pilihan `Tambah Baru` pun akan muncul hal yang sama:

![Todo Add](doc/assets/todo_add.png)

Kamu bisa mengubahnya pada file `translations/forms.id.yaml` dan tambahkan baris berikut:

```yaml
            todo:
                task: 'Tugas'
                done: 'Selesai?'
```

Ketika kamu berusaha menambahkan task, maka akan kamu harus mengeklik check box `done`, untuk memodifikasi itu, kamu harus mengubah definisi form (akan dibahas selanjutnya).

Jangan lupa untuk merestart docker untuk mendapatkan perubahan.

Untuk lebih jelas tentang translasi pada Symfony, kamu dapat membaca dokumentasi resmi tentang [Symfony Translation](https://symfony.com/doc/current/translation.html)

#### Update form type

Secara default, semua field yang ada pada form adalah `required` sehingga kita wajib mengisinya. Untuk mengubahnya, ubah config file `Form/TodoType` pada fungsi `buildForm` sebagai berikut:

```php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('task', null, [
            'required' => true,
            'label' => 'sas.form.field.todo.task',
        ]);
        $builder->add('done', null, [
            'required' => false,
            'label' => 'sas.form.field.todo.done',
        ]);
    }
```

Untuk lebih jelas tentang Symfony Form, kamu dapat membaca dokumentasi resmi tentang [Symfony Form](https://symfony.com/doc/current/forms.html)

Jangan lupa untuk merestart docker untuk mendapatkan perubahan.

Perubahan ini akan berlaku pada halaman Admin maupun Rest Api.

#### Mengaktifkan fungsi pencarian

Secara default, SemartApiSkeleton telah menyiapkan class untuk menghandle kustomasi query, namun SemartApiSkeleton tidak memberikan logic apapun pada class tersebut. Kamu sendiri yang perlu memberikut logic pada class tersebut.

Sebagai contoh, kita akan coba mengaktifkan fungsi pencarian Todo berdasarkan field `task`, maka kita mengubah file `Todo/Query/SearchQueryExtension` pada fungsi `apply` sebagai berikut:

```php
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.task)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))
            )
        );
    }
```

SemartApiSkeleton menggunakan [Doctrine Query Builder](https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/query-builder.html) untuk melakukan kustomasi query pada query extension.

Jangan lupa untuk merestart docker untuk mendapatkan perubahan.

#### Update Template

Secara default, SemartApiSkeleton akan mengubah `bool` menjadi `string` (direpresentasikan dengan `0` dan `1`) seperti pada daftar todo berikut:

![Todo List](doc/assets/todo_template.png)

Kita dapat mengubahnya melalui file `templates/todo/all.html.twig` sebagai berikut:

```twig
{% for property in properties %}
    {% if 'id' != property.name %}
        <tr>
            <td style="width: 149px;">{{ ('sas.table.column.' ~ context ~ '.' ~ property.name) | trans({}, 'tables') }}</td>
            <td style="width: 7px;">:</td>
            {% if 'done' == property.name %}
                <td>{% if todo.done %}Selesai{% else %}Belum Selesai{% endif %}</td>
            {% else %}
                <td>{{ semart_print(attribute(data, property.name)) }}</td>
            {% endif %}
        </tr>
    {% endif %}
{% endfor %}
```

Selain itu, kita juga perlu mengubah file `templates/todo/view.html.twig` sebagai berikut:

```twig
{% for property in properties %}
    {% if 'id' != property.name %}
        <tr>
            <td style="width: 149px;">{{ ('sas.table.column.' ~ context ~ '.' ~ property.name) | trans({}, 'tables') }}</td>
            <td style="width: 7px;">:</td>
            {% if 'done' == property.name %}
                <td>{% if data.done %}Selesai{% else %}Belum Selesai{% endif %}</td>
            {% else %}
                <td>{{ semart_print(attribute(data, property.name)) }}</td>
            {% endif %}
        </tr>
    {% endif %}
{% endfor %}
```

Untuk lebih jelas tentang Twig Template, kamu dapat membaca dokumentasi resmi [Twig Template](https://twig.symfony.com)

Jangan lupa untuk merestart docker untuk mendapatkan perubahan.

## Fitur

>
> * RESTful Api Generator
>
> * Admin Generator
>
> * Api Documentation
>
> * Sandbox
>
> * JWT Authentication
>
> * Rate Limiter
>
> * Single Sign In
>
> * Query Extension
>
> * Soft Deletable
>
> * Audit Trail
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
> * Public & Private Api Support
>
> * Housekeeping
>
> * Password History
>
> * Api Client/Consumer Management
>
> * Health Check
>
> * Export to CSV
>

## Dokumentasi

#### Dasar

- [Penggunaan Dasar](doc/generator.md)

- [Sandbox](doc/sandbox.md)

- [Authentikasi](doc/auth.md)

- [*Form*](doc/form.md)

- [Pencarian](doc/search.md)

- [Pengaturan *Permission*](doc/permission.md)

- [Manajemen Cronjob](doc/cron.md)

- [Menu](doc/menu.md)

- [Api Publik](doc/public.md)

#### Lanjutan

- [Handel Kompleks Query](doc/query_extension.md)

- [Menambah Tipe *User* Baru](doc/user.md)

## Digunakan Oleh

<table>
  <tbody width="100%">
    <tr valign="top">
      <td width="50%" align="center" style="padding-bottom: 17px">
        <span>Kementerian Pariwisata</span><br>
        <span style="font-size: small">(checked: 8-7-2021)</span><br><br> 
        <img height="77px" src="./doc/assets/kemenpar.png">
      </td>
      <td width="50%" align="center">
        <span>Pemprov DKI Jakarta</span><br>
        <span style="font-size: small">(checked: 8-7-2021)</span><br><br> 
        <img height="77px" src="./doc/assets/dki.png">
      </td>
    </tr>
  </tbody>
</table>

## Copyright

Semart Api Skeleton dikembangkan dan dimaintain oleh [KejawenLab](https://github.com/KejawenLab)

## Lisensi

Lisensi dari Semart Api Skeleton adalah [MIT License](LICENSE) namun proyek yang dibangun menyeseuaikan dengan kebijakan masing-masing

## Screenshot

#### Halaman Admin

![Admin](doc/assets/admin.png)

#### Api Doc
![Api Doc](doc/assets/full.png)

#### Sandbox
![Sandbox](doc/assets/sandbox.png)

#### Cronjob Management
![Cronjob](doc/assets/cron.png)

#### Setting Management
![Setting](doc/assets/setting.png)

#### Media Management
![Media](doc/assets/media.png)

#### Group Management
![Group](doc/assets/group.png)

#### Menu Management
![Menu](doc/assets/menu.png)

#### User Management
![User](doc/assets/user.png)

#### Profile Management
![Profile](doc/assets/profile.png)

#### Client/Api Consumer Management
![Consumer](doc/assets/consumer.png)
