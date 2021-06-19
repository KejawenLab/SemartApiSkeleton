# Pencarian pada Semart Api Skeleton

## Pengantar

Salah satu fitur dasar pada sebuah *software* adalah pencarian. Pencarian ada yang bersifat umum atau biasa disebut *full text search* dan ada juga yang bersifat spesifik.

Untuk mengakomodir kebutuhan tersebut, pada Semart Api Skeleton terdapat fitur pencarian yang sangat fleksibel dan dapat disesuaikan dengan berbagai macam kasus sesuai dengna kebutuhan.

## *Search Query Extension*

Semart Api Skeleton mengusung konsep [*Query Extension*](query_extension.md) yang memungkinkan *developer* mengimplementasi segala macam kasus yang berhubungan dengan *query* mulai dari yang mudah hingga yang sangat kompleks sekalipun.

Ketika menggunakan [Semart Generator](generator.md), secara otomatis, akan dibuatkan sebuah *class* yaitu `QuerySearchExtension` pada *folder* `Query` yang mengimplementasika *Doctrine Query Builder* dengan isi sebagai berikut:

```php
<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Application\Test\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Application\Test\Model\TestInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        /**
         * Uncomment to implement your own search logic
         *
         * $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.name)', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
         */
    }

    public function support(string $class): bool
    {
        return in_array(TestInterface::class, class_implements($class));
    }
}

```

Terlihat pada contoh, secara *default*, fitur pencarian masih dikomen. Fitur pencarian ini akan menangkap parameter dari *query string* `q` misalnya `/api/tests?q=belajar`. Jika parameter tersebut tidak ada, maka akan diabaikan.

Untuk mengaktifkannya, kita cukup *uncomment* sebagai berikut:

```php
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.name)', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }
```

Selain itu, kita juga perlu meng-*update* deskripsi dari dokumentasi Api kita pada *controller* `GetAll` pada *folder* `Controller/Test/GetAll` sebagai berikut:

```php
     @OA\Parameter(
         name="q",
         in="query",
         @OA\Schema(
              type="string"
         )
     )
```

Untuk lebih jelas tentang bagaimana cara penggunakan *Doctrine Query Builder* dapat membacanya lebih lengkap pada [dokumentasinya](https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/query-builder.html)
