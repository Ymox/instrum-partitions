<?php

namespace App\Listener;

use App\Entity\Lending;

class LendingListener
{
    public function changeLendingPiecesStatus(Lending $lending, $eventArgs)
    {
        if ($lending->isOurs()) {
            $statusId = $lending->getEnd() ? 'unverified' : 'lent';
        } else {
            $statusId = $lending->getEnd() ? 'returned' : 'unverified';
        }
        $status = $eventArgs->getObjectManager()->getRepository('YSoftInstrumBundle:Status')->find($statusId);

        /** @var $piece \App\Entity\Piece */
        foreach ($lending->getPieces() as &$piece) {
            $piece->setStatus($status);
        }
    }
}