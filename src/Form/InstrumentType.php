<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label_format' => 'app.fields.part.%name%',
            ])
            ->add('key', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'c',
                    'c_flat',
                    'b',
                    'b_flat',
                    'a',
                    'a_flat',
                    'g',
                    'g_flat',
                    'f',
                    'e',
                    'e_flat',
                    'd',
                    'd_flat',
                ],
                'choice_label' => function ($value, $key, $index) {
                    return 'app.fields.instrument.key.choices.' . $value;
                },
                'label_format' => 'app.fields.instrument.key.label',
            ])
            ->add('family', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'wood',
                    'brass',
                    'string',
                    'percussion',
                    'voice',
                ],
                'choice_label' => function ($value, $key, $index) {
                    return 'app.fields.instrument.family.choices.' . $value;
                },
                'label_format' => 'app.fields.instrument.family.label',
            ])
            ->add('common', null, [
                'label_format' => 'app.fields.instrument.%name%.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Instrument::class,
        ]);
    }
}
