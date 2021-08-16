<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Validator\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Validator\PasswordLength;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, [
            'required' => true,
            'label' => 'sas.form.field.user.profileImage',
            'documentation' => [
                'type' => 'string',
                'format' => 'binary',
            ],
        ]);
        $builder->add('group', EntityType::class, [
            'required' => true,
            'class' => Group::class,
        ]);
        $builder->add('supervisor', EntityType::class, [
            'required' => true,
            'class' => User::class,
        ]);
        $builder->add('fullName', TextType::class, ['required' => true]);
        $builder->add('email', EmailType::class, ['required' => true]);
        $builder->add('plainPassword', PasswordType::class, [
            'required' => false,
            'constraints' => [new PasswordHistory(), new PasswordLength()],
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
