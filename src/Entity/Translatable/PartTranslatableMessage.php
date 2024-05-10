<?php

namespace App\Entity\Translatable;

use App\Entity\Part;
use Symfony\Component\Translation\TranslatableMessage;

class PartTranslatableMessage extends TranslatableMessage
{
    const CLEF_CONTEXT_SHORT = 'short';

    const CLEF_CONTEXT_LONG = 'long';

    public function __construct(Part $part, string $clefContext = 'long', ?string $domain = null, string $translationKey = null)
    {
        parent::__construct(
            $translationKey ?? 'app.part.to_string.default',
            [
                'piece_id'   => $part->getPiece()->getId(),
                'instrument' => new InstrumentTranslatableMessage($part->getInstrument()),
                'solo'       => $part->isSolo(),
                'number'     => $part->getNumber() ?: 'EMPTY',
                'clef'       => $part->getClef()
                    ? new TranslatableMessage(sprintf(
                            'app.fields.part.clef.choices.%s.%s',
                            $clefContext,
                            $part->getClef()
                        ))
                    : 'EMPTY'
            ],
            $domain
        );
    }
}