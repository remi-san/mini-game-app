<?php
namespace MiniGameApp\Test\Mock;

use Broadway\Domain\DomainMessage;
use League\Event\EventInterface;
use MiniGameApp\Manager\AbstractGameManager;

class TestGameManager extends AbstractGameManager
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
