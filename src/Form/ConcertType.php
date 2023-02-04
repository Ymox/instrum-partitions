<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.concert.%name%'
            ])
            ->add('date', null, [
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'label_format' => 'app.fields.concert.%name%',
            ])
            ->add('pieces', null, [
                'choice_label' => function($piece) {
                    $pieceAsString = $piece->getId() . ' - ' . $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                    return $pieceAsString;
                },
                'label_format' => 'app.fields.concert.%name%',
                'attr' => [
                    'class' => 'searchable',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Concert::class
        ]);
    }
}
