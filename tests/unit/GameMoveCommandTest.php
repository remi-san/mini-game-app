<?php
namespace MiniGameApp\Test;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Move;
use MiniGameApp\Command\GameMoveCommand;
use RemiSan\Context\Context;

class GameMoveCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlayerId */
    private $playerId;

    /** @var MiniGameId */
    private $gameId;

    /** @var Move */
    private $move;

    /** @var Context */
    private $context;

    public function setUp()
    {
        $faker = Factory::create();

        $this->playerId = PlayerId::create($faker->uuid);
        $this->gameId = MiniGameId::create($faker->uuid);
        $this->move = \Mockery::mock(Move::class);
        $this->context = \Mockery::mock(Context::class);
    }

    /**
     * @test
     */
    public function itShouldBuildTheCommand()
    {
        $command = GameMoveCommand::create($this->gameId, $this->playerId, $this->move, $this->context);

        $this->assertEquals($this->playerId, $command->getPlayerId());
        $this->assertEquals($this->gameId, $command->getGameId());
        $this->assertEquals($this->move, $command->getMove());
        $this->assertEquals($this->context, $command->getContext());
        $this->assertEquals(GameMoveCommand::NAME, $command->getCommandName());
    }
}
