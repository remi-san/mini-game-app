<?php

namespace MiniGameApp\Event;

class UnableToCreateGameEvent extends MiniGameAppErrorEvent
{
    const NAME = 'minigame.error.create';
}
