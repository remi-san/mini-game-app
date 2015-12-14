<?php
namespace MiniGameApp\Test\Mock;

use Broadway\Domain\DomainMessage;
use League\Event\EventInterface;
use MiniGameApp\Repository\AbstractGameRepository;

class TestGameRepository extends AbstractGameRepository
{
    /**
     * Prepares the event to return a League Event
     *
     * @param  DomainMessage $originalEvent
     * @return EventInterface
     */
    protected function prepareEvent($originalEvent)
    {
        return $originalEvent->getPayload();
    }
}
