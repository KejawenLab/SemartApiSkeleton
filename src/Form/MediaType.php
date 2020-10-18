<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use KejawenLab\ApiSkeleton\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, [
            'required' => true,
            'label' => 'sas.form.field.media.file',
        ]);
        $builder->add('folder', TextType::class, [
            'required' => true,
            'label' => 'sas.form.field.media.folder',
        ]);
        $builder->add('public', CheckboxType::class, [
            'required' => false,
            'label' => 'sas.form.field.media.public',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'translation_domain' => 'forms',
        ]);
    }
}
