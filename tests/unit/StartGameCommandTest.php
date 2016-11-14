<?php
namespace MiniGameApp\Test;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Command\StartGameCommand;
use RemiSan\Context\Context;

class StartGameCommandTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @test
     */
    public function itShouldBuildTheCommand()
    {
        $command = StartGameCommand::create($this->gameId, $this->playerId, $this->context);

        $this->assertEquals($this->playerId, $command->getPlayerId());
        $this->assertEquals($this->gameId, $command->getGameId());
        $this->assertEquals($this->context, $command->getContext());
        $this->assertEquals(StartGameCommand::NAME, $command->getCommandName());
    }
}
