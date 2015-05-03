<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InMemoryPlayerManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class TestPlayerManager extends InMemoryPlayerManager {}

class InMemoryPlayerManagerTest extends \PHPUnit_Framework_TestCase {
    use GameObjectMocker;
    use MiniGameAppMocker;
    use MessageAppMocker;

    private $playerId = 1;
    private $playerName = 'user';

    private $player;

    private $user;

    public function setUp() {

        $this->user = $this->getApplicationUser($this->playerId, $this->playerName);
        $this->player = $this->getPlayer($this->playerId, $this->playerName);
    }

    /**
     * @test
     */
    public function testConstructor() {

        $manager = new TestPlayerManager(array($this->playerId, $this->player));

        $this->assertEquals($this->player, $manager->getPlayer($this->user));
    }

    /**
     * @test
     */
    public function testSave() {

        $manager = new TestPlayerManager();
        $manager->savePlayer($this->player);

        $this->assertEquals($this->player, $manager->getPlayer($this->user));
    }

    /**
     * @test
     */
    public function testCreate() {

        $manager = new TestPlayerManager();
        $manager->createPlayer($this->user);

        $this->assertEquals($this->playerId, $manager->getPlayer($this->user)->getId());
        $this->assertEquals($this->playerName, $manager->getPlayer($this->user)->getName());
    }

    /**
     * @test
     */
    public function testCreateWhenNotExisting() {

        $manager = new TestPlayerManager();
        $player = $manager->getPlayer($this->user);

        $this->assertEquals($this->playerId, $player->getId());
        $this->assertEquals($this->playerName, $player->getName());
    }
} 