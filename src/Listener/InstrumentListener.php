<?php

namespace App\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class InstrumentListener
{
    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    public function __construct(\Symfony\Contracts\Translation\TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function postLoad(\App\Entity\Instrument $entity)
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