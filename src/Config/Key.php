<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Key: string implements TranslatableInterface
{
    case C = 'c';
    case C_FLAT = 'c_flat';
    case B = 'b';
    case B_FLAT = 'b_flat';
    case A = 'a';
    case A_FLAT = 'a_flat';
    case G = 'g';
    case G_FLAT = 'g_flat';
    case F = 'f';
    case E = 'e';
    case E_FLAT = 'e_flat';
    case D = 'd';
    case D_FLAT = 'd_flat';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('app.fields.instrument.key.choices.' . $this->value, [], locale: $locale);
    }
}