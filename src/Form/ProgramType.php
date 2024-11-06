<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.program.%name%'
            ])
            ->add('pieces', null, [
                'choice_label' => function($piece) {
                    $pieceAsString = $piece->getId() . ' - ' . $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                    return $pieceAsString;
                },
                'by_reference' => false,
                'label_format' => 'app.fields.program.%name%',
                'attr' => [
                    'class' => 'searchable',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Program::class
        ]);
    }
}
