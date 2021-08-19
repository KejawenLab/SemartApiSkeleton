<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Validator\PasswordLength;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('group', EntityType::class, [
            'required' => true,
            'class' => Group::class,
            'choice_label' => fn($group): string => sprintf('%s - %s', $group->getCode(), $group->getName()),
            'label' => 'sas.form.field.user.group',
            'attr' => [
                'class' => 'select2',
            ],
            'placeholder' => 'sas.form.field.empty_select',
        ]);
        $builder->add('supervisor', EntityType::class, [
            'required' => false,
            'class' => User::class,
            'choice_label' => fn($supervisor): string => sprintf('%s (%s)', $supervisor->getFullName(), $supervisor->getUsername()),
            'label' => 'sas.form.field.user.supervisor',
            'attr' => [
                'class' => 'select2',
            ],
            'placeholder' => 'sas.form.field.empty_select',
        ]);
        $builder->add('fullName', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.user.fullName',
        ]);
        $builder->add('username', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.user.username',
        ]);
        $builder->add('email', EmailType::class, [
            'required' => true,
            'label' => 'sas.form.field.user.email',
        ]);
        $builder->add('plainPassword', PasswordType::class, [
            'required' => false,
            'constraints' => [new PasswordLength()],
            'label' => 'sas.form.field.user.plainPassword',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms',
        ]);
    }
}
