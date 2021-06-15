<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('parameter', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.setting.parameter',
        ]);
        $builder->add('value', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.setting.value',
        ]);
        $builder->add('public', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.setting.public',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
            'translation_domain' => 'forms',
        ]);
    }
}
