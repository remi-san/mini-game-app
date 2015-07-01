<?php
namespace MiniGameApp\Test;

use MiniGame\GameOptions;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InMemoryGameManager;

class TestGameManager extends InMemoryGameManager {
    public function createMiniGame(GameOptions $options) {
        return null;
    }
}

class InMemoryGameManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    private $wordSelector;

    private $miniGame;

    private $player;

    public function setUp()
    {
        $this->wordSelector = \Mockery::mock('WordSelector\\WordSelector');

        $this->player = $this->getPlayer(1, 'player');

        $this->miniGame = $this->getMiniGame(self::ID, 'Game');
        $this->miniGame->shouldReceive('getPlayers')->andReturn(array($this->player));
    }

    /**
     * @test
     */
    public function testGetMiniGame()
    {

        $manager = new TestGameManager(array(self::ID, $this->miniGame), array());
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame(self::ID));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->getMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testGetActiveMiniGameForPlayer()
    {

        $manager = new TestGameManager(array(self::ID, $this->miniGame), array($this->player->getId(), $this->miniGame));
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getActiveMiniGameForPlayer($this->player));
    }

    /**
     * @test
     */
    public function testGetNonExistingActiveMiniGameForPlayer()
    {

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->getActiveMiniGameForPlayer($this->player);
    }

    /**
     * @test
     */
    public function testDeleteMiniGame()
    {

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager(array(self::ID, $this->miniGame));
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame(self::ID));

        $manager->deleteMiniGame(self::ID);
        $manager->getMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testDeleteNonExistingMiniGame()
    {

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->deleteMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->saveMiniGame($this->miniGame);

        $this->assertEquals($this->miniGame, $manager->getMiniGame(self::ID));
    }
}