<?php

namespace YSoft\InstrumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label_format' => 'ysoft.instrum.fields.concert.%name%'
            ))
            ->add('date', null, array(
                'label_format' => 'ysoft.instrum.fields.concert.%name%'
            ))
            ->add('pieces', null, array(
                'choice_label' => function($piece) {
                    $pieceAsString = $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                    return $pieceAsString;
                },
                'label_format' => 'ysoft.instrum.fields.concert.%name%'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YSoft\InstrumBundle\Entity\Concert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ysoft_instrumbundle_concert';
    }


}
