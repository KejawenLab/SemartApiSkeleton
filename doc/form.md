# Form pada Semart Api Skeleton

## Pengantar

Dengan menggunakan [Semart Generator](generator.md), Semart Api Skeleton secara otomatis akan membuatkan *form class* pada *folder* `Form` dengan format `[ENTITY_CLASS_NAME]Type.php`.

Secara otomatis, Semart Generator akan men-*generate* semua *property* yang ada pada *entity class* yang bertipe `private` dengan hasil yang sama persis seperti menggunakan perintah `php bin/console make:form` sebagai berikut:

```php
<?php

declare(strict_types=1);

namespace Kejawenlab\Application\Form;

use Kejawenlab\Application\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}

```

Penggunaan Symfony *Form* bertujuan agar semua fitur *form* pada *component* tersebut dapat digunakan serta lebih mudah bagi *developer* dalam belajar.

## Cara kerja Symfony *Form*

Secara *default*, bila tipe *field* pada *form* tidak didefiniskan seperti di atas, Symfony *Form* akan membaca *metadata* dari *entity* sebagai rujukannya. Fitur tersebut sangatlah *powerful* untuk mempercepat kerja kita dalam membangun aplikasi.

Seperti pada contoh di atas, `$builder->add('name')`, *field* `name` tidak memiliki tipe apapun, maka Symfony *Form* akan menganggapnya sebagai *input text* biasa karena pada *entity*, *field* `name` didefinisikan sebagai `string` (`@ORM\Column(type="string", length=255)`) atau sama dengan `$builder->add('name', TextType::class)` jika didefinisikan secara eksplisit.

Untuk lebih jelas tentang Symfony *Form* pada langsung membaca [dokumentasinya](https://symfony.com/doc/current/forms.html) 
