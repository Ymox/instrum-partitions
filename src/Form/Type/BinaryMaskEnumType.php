<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class BinaryMaskEnumType extends BinaryMaskChoiceType
{
    public function getParent(): ?string
    {
        return \Symfony\Component\Form\Extension\Core\Type\EnumType::class;
    }

    public function transform(mixed $modelData): mixed
    {
        $formData = [];
        if (!is_int($modelData)) {
            throw new UnexpectedTypeException($modelData, 'integer');
        }

        foreach ($this->choices as $power) {
            if ($modelData & $power->value) {
                $formData[] = $power;
            }
        }

        return $formData;
    }

    public function reverseTransform(mixed $formData): mixed
    {
        $result = 0;
        foreach ($formData as $power) {
            $result += $power->value;
        }

        return $result;
    }
}
