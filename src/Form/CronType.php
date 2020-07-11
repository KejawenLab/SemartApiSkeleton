<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Form;

use Alpabit\ApiSkeleton\Entity\Cron;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => true]);
        $builder->add('description', TextType::class, ['required' => false]);
        $builder->add('command', TextType::class, ['required' => true]);
        $builder->add('schedule', TextType::class, ['required' => true]);
        $builder->add('enabled', CheckboxType::class, ['required' => true]);
        $builder->add('symfonyCommand', CheckboxType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cron::class,
        ]);
    }
}
