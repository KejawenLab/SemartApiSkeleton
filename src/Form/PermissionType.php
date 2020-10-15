<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Entity\Permission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('menu', EntityType::class, [
            'required' => true,
            'class' => Menu::class,
        ]);
        $builder->add('viewable', CheckboxType::class, ['required' => true]);
        $builder->add('addable', CheckboxType::class, ['required' => true]);
        $builder->add('editable', CheckboxType::class, ['required' => true]);
        $builder->add('deletable', CheckboxType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Permission::class,
            'translation_domain' => 'forms',
        ]);
    }
}
