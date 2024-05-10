<?php

namespace App\Entity\Translatable;

use App\Entity\Instrument;
use Symfony\Component\Translation\TranslatableMessage;

class InstrumentTranslatableMessage extends TranslatableMessage
{
    public function __construct(Instrument $instrument, ?string $domain = null)
    {
        parent::__construct(
            'app.fields.part.instrument.choices',
            [
                'instrument' => $instrument->getName(),
                'key'        => $instrument->getKey()
                    ? new TranslatableMessage('app.fields.instrument.key.choices.' . $instrument->getKey()) 
                    : 'EMPTY',
            ],
            $domain
        );
    }
}