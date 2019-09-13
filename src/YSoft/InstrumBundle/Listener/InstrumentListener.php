<?php

namespace YSoft\InstrumBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use YSoft\InstrumBundle\Entity\Instrument;

class InstrumentListener
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    public function __construct(\Symfony\Component\Translation\TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Instrument) {
            return;
        }

        if ($entity->getKey()) {
            $displayName = $this->translator->trans('ysoft.instrum.fields.part.instrument.choices', array(
                '%instrument%' => $entity->getName(),
                '%key%' => $this->translator->trans('ysoft.instrum.fields.instrument.key.choices.' . $entity->getKey())
            ));
        } else {
            $displayName = $entity->getName();
        }

        $entity->setDisplay($displayName);
    }
}