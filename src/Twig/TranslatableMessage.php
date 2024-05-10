<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use App\Entity\Instrument;
use App\Entity\Part;
use App\Entity\Translatable\InstrumentTranslatableMessage;
use App\Entity\Translatable\PartTranslatableMessage;

class TranslatableMessage extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('part_t', [$this, 'getPartTranslationMessage']),
            new \Twig\TwigFunction('instrument_t', [$this, 'getInstrumentTranslationMessage']),
        ];
    }

    public function getPartTranslationMessage(Part $part, string $clefContect = PartTranslatableMessage::CLEF_CONTEXT_LONG, ?string $domain = null, string $translationKey = null)
    {
        return new PartTranslatableMessage($part, $clefContect, $domain, $translationKey);
    }

    public function getInstrumentTranslationMessage(Instrument $instrument, ?string $domain = null)
    {
        return new InstrumentTranslatableMessage($instrument, $domain);
    }
}