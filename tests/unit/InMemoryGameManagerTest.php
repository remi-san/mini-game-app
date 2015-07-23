<?php
namespace MiniGameApp\Test;

use MiniGame\GameOptions;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InMemoryGameManager;
use MiniGameApp\Test\Mock\TestGameManager;

class InMemoryGameManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    private $miniGame;

    private $player;

    public function setUp()
    {
        $this->player = $this->getPlayer(1, 'player');

        $this->miniGame = $this->getMiniGame(self::ID, 'Game');
        $this->miniGame->shouldReceive('getPlayers')->andReturn(array($this->player));
    }

    public function tearDown()
    {
        \Mockery::close();
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

        $manager = new TestGameManager(
            array(self::ID, $this->miniGame),
            array($this->player->getId(),$this->miniGame)
        );
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
