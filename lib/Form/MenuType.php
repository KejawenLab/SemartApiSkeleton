<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('parent', EntityType::class, [
            'required' => false,
            'label' => 'sas.form.field.menu.parent',
            'class' => Menu::class,
            'choice_label' => fn($menu) => sprintf('%s - %s', $menu->getCode(), $menu->getName()),
            'attr' => [
                'class' => 'select2',
            ],
            'placeholder' => 'sas.form.field.empty_select',
        ]);
        $builder->add('code', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.menu.code',
        ]);
        $builder->add('name', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.menu.name',
        ]);
        $builder->add('routeName', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.menu.routeName',
        ]);
        $builder->add('iconClass', TextType::class, [
            'required' => false,
            'label' => 'sas.form.field.menu.iconClass',
        ]);
        $builder->add('sortOrder', NumberType::class, [
            'required' => true,
            'label' => 'sas.form.field.menu.sortOrder',
        ]);
        $builder->add('showable', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.menu.showable',
        ]);
        $builder->add('adminOnly', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.menu.adminOnly',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'translation_domain' => 'forms',
        ]);
    }
}
