<?php

namespace App\Listener;

use App\Entity\Instrument;
use Symfony\Contracts\Translation\TranslatorInterface;

class InstrumentListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function postLoad(Instrument $entity)
    {
        if ($entity->getKey()) {
            $displayName = $this->translator->trans('app.fields.part.instrument.choices', [
                'instrument' => $entity->getName(),
                'key' => $this->translator->trans('app.fields.instrument.key.choices.' . $entity->getKey())
            ]);
        } else {
            $displayName = $entity->getName();
        }

        $entity->setDisplay($displayName);
    }
}