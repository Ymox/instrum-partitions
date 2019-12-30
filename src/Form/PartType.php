<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PartType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('instrument', null, array(
                'required' => true,
                'group_by' => function ($value, $key, $index) {
                    if ($value->isCommon()) {
                        return 'app.fields.instrument.common.true';
                    } else {
                        return 'app.fields.instrument.common.false';
                    }
                },
                'label_format' => 'app.fields.part.%name%.label',
            ))
            ->add('clef', ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'g',
                    'c',
                    'f',
                ),
                'choice_label' => function ($value, $key, $index) {
                    return 'app.fields.part.clef.choices.short.' . $value;
                },
                'label_format' => 'app.fields.part.%name%.label',
            ))
            ->add('number', IntegerType::class, array(
                'required' => false,
                'label_format' => 'app.fields.part.%name%',
            ))
            ->add('solo', CheckboxType::class, array(
                'required' => false,
                'label_format' => 'app.fields.part.%name%',
            ))
            ->add('upload', FileType::class, array(
                'label_format' => 'app.fields.part.%name%',
                'file_property' => 'downloadPath',
                'required' => false,
            ))
            ->add('file', HiddenType::class, array(
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Part::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_part';
    }
}
