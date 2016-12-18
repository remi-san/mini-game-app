<?php
namespace MiniGameApp\Test\Command;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGameApp\Test\Mock\ConcretePlayerCommand;
use RemiSan\Context\Context;

class PlayerCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlayerId */
    private $playerId;

    /** @var MiniGameId */
    private $gameId;

    /** @var Context */
    private $context;

    public function setUp()
    {
        $faker = Factory::create();

        $this->playerId = PlayerId::create($faker->uuid);
        $this->gameId = MiniGameId::create($faker->uuid);
        $this->context = \Mockery::mock(Context::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildTheCommand()
    {
        $playerCommand = new ConcretePlayerCommand($this->gameId, $this->playerId, $this->context);

        $this->assertEquals($this->playerId, $playerCommand->getPlayerId());
        $this->assertEquals($this->gameId, $playerCommand->getGameId());
        $this->assertEquals($this->context, $playerCommand->getContext());
    }
}
