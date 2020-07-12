<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Form;

use Alpabit\ApiSkeleton\Entity\Client;
use Alpabit\ApiSkeleton\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', EntityType::class, [
            'required' => false,
            'class' => User::class,
        ]);
        $builder->add('apiKey', TextType::class, ['required' => true]);
        $builder->add('secretKey', TextType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
