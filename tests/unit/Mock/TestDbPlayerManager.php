<?php
namespace MiniGameApp\Test\Mock;

use MiniGameApp\Manager\InDatabasePlayerManager;

class TestDbPlayerManager extends InDatabasePlayerManager
{
    public function getByObject($object)
    {
    }

    public function create($object)
    {
    }

    protected function supports($object)
    {
        return $object !== null;
    }
}
