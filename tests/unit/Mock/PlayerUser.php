<?php
namespace MiniGameApp\Test\Mock;

use MessageApp\ApplicationUser;
use MiniGame\Player;

class PlayerUser implements Player, ApplicationUser
{
    public function getId()
    {
        return 1;
    }

    public function getName()
    {
        return 'John';
    }
}
