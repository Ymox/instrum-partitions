<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SizeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
            ->add('minWidth', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
            ->add('maxWidth', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
            ->add('minHeight', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
            ->add('maxHeight', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
            ->add('note', null, array(
                'label_format' => 'app.fields.size.%name%',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Size::class
        ));
    }
}
