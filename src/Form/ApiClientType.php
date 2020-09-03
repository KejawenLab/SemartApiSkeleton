<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ApiClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', EntityType::class, [
            'required' => true,
            'class' => User::class,
            'label' => 'sas.form.field.api_client.user',
            'choice_label' => function ($user) {
                return sprintf('%s - %s', $user->getFullName(), $user->getUsername());
            },
            'attr' => [
                'class' => 'select2',
            ],
            'placeholder' => 'sas.form.field.empty_select',
        ]);
        $builder->add('name', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.api_client.name',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApiClient::class,
        ]);
    }
}
