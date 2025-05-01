<?php

namespace App\Form;

use App\Config\Family;
use App\Config\Key;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label_format' => 'app.fields.instrument.%name%',
            ])
            ->add('key', EnumType::class, [
                'required' => false,
                'class' => Key::class,
                'placeholder' => 'app.fields.instrument.key.placeholder',
                'label_format' => 'app.fields.instrument.%name%.label',
            ])
            ->add('family', EnumType::class, [
                'required' => false,
                'class' => Family::class,
                'placeholder' => 'app.fields.instrument.family.placeholder',
                'label_format' => 'app.fields.instrument.%name%.label',
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
