<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Form;

use Alpabit\ApiSkeleton\Entity\Group;
use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Security\Validator\PasswordLength;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('group', EntityType::class, [
            'required' => true,
            'class' => Group::class,
        ]);
        $builder->add('supervisor', EntityType::class, [
            'required' => true,
            'class' => User::class,
        ]);
        $builder->add('fullName', TextType::class, ['required' => true]);
        $builder->add('username', TextType::class, ['required' => true]);
        $builder->add('email', EmailType::class, ['required' => true]);
        $builder->add('plainPassword', PasswordType::class, [
            'required' => false,
            'constraints' => [new PasswordLength()],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
