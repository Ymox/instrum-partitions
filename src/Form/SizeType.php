<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
            ->add('minWidth', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
            ->add('maxWidth', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
            ->add('minHeight', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
            ->add('maxHeight', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
            ->add('note', null, [
                'label_format' => 'app.fields.size.%name%',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Size::class
        ]);
    }
}
