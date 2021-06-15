<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Cron;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.cron.name',
        ]);
        $builder->add('description', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.cron.description',
        ]);
        $builder->add('command', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.cron.command',
        ]);
        $builder->add('schedule', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.cron.schedule',
        ]);
        $builder->add('enabled', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.cron.enabled',
        ]);
        $builder->add('symfonyCommand', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.cron.symfonyCommand',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cron::class,
            'translation_domain' => 'forms',
        ]);
    }
}
