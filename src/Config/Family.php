<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Family: string implements TranslatableInterface
{
    case WOOD = 'wood';
    case BRASS = 'brass';
    case STRING = 'string';
    case PERCUSSION = 'percussion';
    case VOICE = 'voice';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('app.fields.instrument.family.choices.' . $this->value, [], locale: $locale);
    }
}