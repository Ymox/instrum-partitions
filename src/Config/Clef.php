<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Clef: string implements TranslatableInterface
{
    case G = 'g';
    case F = 'f';
    case C = 'c';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('app.fields.part.clef.choices.note.' . $this->value, [], locale: $locale);
    }
}