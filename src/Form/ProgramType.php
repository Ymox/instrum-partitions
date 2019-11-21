<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label_format' => 'app.fields.program.%name%'
            ))
            ->add('pieces', null, array(
                'choice_label' => function($piece) {
                    $pieceAsString = $piece->getId() . ' - ' . $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                    return $pieceAsString;
                },
                'by_reference' => false,
                'label_format' => 'app.fields.program.%name%'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Program'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_program';
    }


}
