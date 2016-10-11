<?php

namespace MiniGameApp\Event;

class UnableToAddPlayerEvent extends MiniGameAppErrorEvent
{
    const NAME = 'minigame.error.player';
}
