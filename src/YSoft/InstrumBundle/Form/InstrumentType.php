<?php

namespace YSoft\InstrumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InstrumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label_format' => 'ysoft.instrum.fields.part.%name%',
            ))
            ->add('key', ChoiceType::class, array(
                'required' => false,
                'choices' => array(
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
                ),
                'choice_label' => function ($value, $key, $index) {
                    return 'ysoft.instrum.fields.instrument.key.choices.' . $value;
                },
                'label_format' => 'ysoft.instrum.fields.instrument.key.label',
            ))
            ->add('family', ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'wood',
                    'brass',
                    'string',
                    'percussion',
                    'voice',
                ),
                'choice_label' => function ($value, $key, $index) {
                    return 'ysoft.instrum.fields.instrument.family.choices.' . $value;
                },
                'label_format' => 'ysoft.instrum.fields.instrument.family.label',
            ))
            ->add('common', null, array(
                'label_format' => 'ysoft.instrum.fields.instrument.%name%.label',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \YSoft\InstrumBundle\Entity\Instrument::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ysoft_instrumbundle_instrument';
    }
}
